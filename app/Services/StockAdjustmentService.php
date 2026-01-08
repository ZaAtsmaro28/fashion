<?php

// app/Services/StockAdjustmentService.php
namespace App\Services;

use App\Models\ProductVariant;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class StockAdjustmentService
{
    protected $mutationService;
    protected $variantService;

    public function __construct(
        StockMutationService $mutationService,
        ProductVariantService $variantService
    ) {
        $this->mutationService = $mutationService;
        $this->variantService = $variantService;
    }

    public function adjust(array $data)
    {
        return DB::transaction(function () use ($data) {
            $variant = ProductVariant::findOrFail($data['product_variant_id']);

            // 1. Update Stok di ProductVariant
            if ($data['type'] === 'in') {
                $variant->increment('stock', $data['quantity']);
            } else {
                // Opsional: Cek apakah stok cukup jika dikurangi
                if ($variant->stock < $data['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk dikurangi.");
                }
                $variant->decrement('stock', $data['quantity']);
            }

            // 2. Catat Mutasi
            $mutation = $this->mutationService->recordMutation(
                $variant->id,
                $data['quantity'],
                $data['type'],
                'Adjustment',
                null, // Tidak ada reference_id (manual adjustment)
                $data['description']
            );

            // 3. Sinkronisasi Stok Induk (Product)
            $this->variantService->syncParentProductStock($variant->product_id);

            return $mutation;
        });
    }
}
