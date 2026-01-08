<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllProducts($search = null, $categoryId = null)
    {
        return $this->repository->getAll($search, $categoryId);
    }

    public function getProductById($id)
    {
        return $this->repository->findById($id);
    }

    public function storeProduct(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        $data['stock'] = 0;

        if (isset($data['image']) && $data['image']->isValid()) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->repository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->repository->findById($id);

        $data = Arr::except($data, ['stock']);

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (isset($data['image']) && $data['image']->isValid()) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        $product = $this->repository->findById($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $this->repository->delete($id);
    }
}
