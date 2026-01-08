<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $orderRepo;
    protected $variantService;
    protected $mutationService;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        ProductVariantService $variantService,
        StockMutationService $mutationService
    ) {
        $this->orderRepo = $orderRepo;
        $this->variantService = $variantService;
        $this->mutationService = $mutationService;
    }

    // app/Services/OrderService.php

    public function checkout(array $data)
    {
        return DB::transaction(function () use ($data) {
            $invoice = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

            $totalCalculatedPrice = 0;
            $itemsToCreate = [];
            $productIdsToSync = [];

            foreach ($data['items'] as $item) {
                // Kita load relasi 'product' agar bisa mengambil harga dasarnya
                $variant = ProductVariant::with('product')->lockForUpdate()->findOrFail($item['product_variant_id']);

                // LOGIKA HARGA: Harga Produk + Harga Tambahan Varian
                $basePrice = $variant->product->price;
                $additional = $variant->additional_price ?? 0;
                $finalPriceAtSale = $basePrice + $additional;

                $subtotal = $finalPriceAtSale * $item['quantity'];
                $totalCalculatedPrice += $subtotal;

                $itemsToCreate[] = [
                    'product_id'         => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity'           => $item['quantity'],
                    'price_at_sale'      => $finalPriceAtSale, // Ini nilainya sekarang sudah ada (tidak null)
                    'subtotal'           => $subtotal,
                ];

                // Operasi Stok & Sinkronisasi
                $variant->decrement('stock', $item['quantity']);
                $productIdsToSync[] = $variant->product_id;
            }

            // Simpan Header Order
            $order = $this->orderRepo->create([
                'invoice_number' => $invoice,
                'user_id'        => Auth::id(),
                'customer_name'  => $data['customer_name'] ?? 'Guest',
                'total_price'    => $totalCalculatedPrice,
                'paid_amount'    => $data['total_pay'],
                'change_amount'  => $data['total_pay'] - $totalCalculatedPrice,
            ]);

            // Simpan Detail & Mutasi (Sama seperti sebelumnya)
            foreach ($itemsToCreate as $itemData) {
                $itemData['order_id'] = $order->id;
                $this->orderRepo->createItem($itemData);

                $this->mutationService->recordMutation(
                    $itemData['product_variant_id'],
                    $itemData['quantity'],
                    'out',
                    'Order',
                    $order->id,
                    "Penjualan Invoice: $invoice"
                );
            }

            // Sinkronisasi Stok Parent
            foreach (array_unique($productIdsToSync) as $productId) {
                $this->variantService->syncParentProductStock($productId);
            }

            return $order->load(['items.product', 'user']);
        });
    }

    // app/Services/OrderService.php

    public function voidOrder($orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = $this->orderRepo->find($orderId); // Pastikan repository punya method find

            if (!$order) throw new \Exception("Transaksi tidak ditemukan.");
            if ($order->status === 'cancelled') throw new \Exception("Transaksi sudah dibatalkan sebelumnya.");

            $productIdsToSync = [];

            foreach ($order->items as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);

                if ($variant) {
                    // 1. Tambahkan Stok Kembali
                    $variant->increment('stock', $item->quantity);

                    // 2. Catat Mutasi Masuk (In) karena Void
                    $this->mutationService->recordMutation(
                        $variant->id,
                        $item->quantity,
                        'in',
                        'Void',
                        $order->id,
                        "Pembatalan Transaksi: {$order->invoice_number}"
                    );

                    $productIdsToSync[] = $variant->product_id;
                }
            }

            // 3. Update Status Order
            $order->update(['status' => 'cancelled']);

            // 4. Sinkronisasi Stok Parent Product
            foreach (array_unique($productIdsToSync) as $productId) {
                $this->variantService->syncParentProductStock($productId);
            }

            return $order;
        });
    }
}
