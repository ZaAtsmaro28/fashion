<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Contracts\ProductVariantRepositoryInterface;

class ProductVariantRepository implements ProductVariantRepositoryInterface
{
    public function getByProductId($productId)
    {
        return ProductVariant::where('product_id', $productId)->get();
    }

    public function findById($id)
    {
        return ProductVariant::findOrFail($id);
    }

    public function create(array $data)
    {
        return ProductVariant::create($data);
    }

    public function update($id, array $data)
    {
        $variant = $this->findById($id);
        $variant->update($data);
        return $variant;
    }

    public function delete($id)
    {
        $variant = $this->findById($id);
        return $variant->delete();
    }
}
