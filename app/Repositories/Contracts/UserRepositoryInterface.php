<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function getAll($search = null, $perPage = 10);
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
