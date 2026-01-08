<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    public function getAll($search = null);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
