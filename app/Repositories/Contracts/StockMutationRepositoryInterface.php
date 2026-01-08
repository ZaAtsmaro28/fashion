<?php

// app/Repositories/Contracts/StockMutationRepositoryInterface.php
namespace App\Repositories\Contracts;

interface StockMutationRepositoryInterface
{
    public function create(array $data);
    public function getFiltered(array $params);
}
