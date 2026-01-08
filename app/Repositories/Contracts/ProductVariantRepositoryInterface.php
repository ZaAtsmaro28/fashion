<?php

namespace App\Repositories\Contracts;

interface ProductVariantRepositoryInterface
{
    public function getByProductId($productId);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
