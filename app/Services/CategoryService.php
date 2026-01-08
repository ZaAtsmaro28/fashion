<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService
{
    protected $repository;

    // Sekarang menggunakan Interface, bukan Class konkret
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getCategories($search)
    {
        return $this->repository->getAll($search);
    }

    public function storeCategory($data)
    {
        $data['slug'] = Str::slug($data['name']);
        return $this->repository->create($data);
    }

    public function updateCategory($id, $data)
    {
        $data['slug'] = Str::slug($data['name']);
        return $this->repository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->repository->delete($id);
    }
}
