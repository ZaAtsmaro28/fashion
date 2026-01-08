<?php

namespace App\Services;

use App\Repositories\Contracts\ProductVariantRepositoryInterface;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductVariantService
{
    protected $repository;
    protected $mutationService;

    public function __construct(ProductVariantRepositoryInterface $repository, StockMutationService $mutationService)
    {
        $this->repository = $repository;
        $this->mutationService = $mutationService;
    }

    public function storeVariant(array $data)
    {
        $variant = $this->repository->create($data);
        $this->syncParentProductStock($variant->product_id);
        return $variant;
    }

    public function updateVariant($id, array $data)
    {
        $variant = $this->repository->update($id, $data);
        $this->syncParentProductStock($variant->product_id);
        return $variant;
    }

    public function deleteVariant($id)
    {
        $variant = $this->repository->findById($id);
        $productId = $variant->product_id;

        $deleted = $this->repository->delete($id);
        $this->syncParentProductStock($productId);

        return $deleted;
    }

    /**
     * Sinkronisasi stok produk utama berdasarkan total stok semua varian
     */
    public function syncParentProductStock($productId)
    {
        $totalStock = ProductVariant::where('product_id', $productId)->sum('stock');
        Product::where('id', $productId)->update(['stock' => $totalStock]);
    }

    public function bulkRestock(array $items)
    {
        return DB::transaction(function () use ($items) {
            $productIdsToSync = [];

            foreach ($items as $item) {
                $variant = ProductVariant::lockForUpdate()->find($item['product_variant_id']);

                // 1. Tambahkan Stok
                $variant->increment('stock', $item['quantity_added']);

                // 2. Catat Mutasi Masuk (In)
                $this->mutationService->recordMutation(
                    $variant->id,
                    $item['quantity_added'],
                    'in',
                    'Restock',
                    null, // Tidak ada order_id karena ini pembelian stok
                    "Penambahan stok manual/restok supplier"
                );

                $productIdsToSync[] = $variant->product_id;
            }

            // 3. Sinkronisasi stok total di tabel products
            foreach (array_unique($productIdsToSync) as $productId) {
                $this->syncParentProductStock($productId);
            }

            return true;
        });
    }
}
