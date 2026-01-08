<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function getAll($search = null, $categoryId = null);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function countAll();
    public function countCriticalStock($threshold);
    public function getTopSelling($limit = 5);
    public function getLowStockProducts($limit = 5);
    public function getRestockList($threshold = 5);
}
