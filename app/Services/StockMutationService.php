<?php

// app/Services/StockMutationService.php
namespace App\Services;

use App\Repositories\Contracts\StockMutationRepositoryInterface;

class StockMutationService
{
    protected $mutationRepo;

    public function __construct(StockMutationRepositoryInterface $mutationRepo)
    {
        $this->mutationRepo = $mutationRepo;
    }

    /**
     * Mencatat mutasi stok
     */
    public function recordMutation($variantId, $quantity, $type, $referenceType, $referenceId, $description)
    {
        return $this->mutationRepo->create([
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
        ]);
    }

    public function getHistory(array $params)
    {
        return $this->mutationRepo->getFiltered($params);
    }
}
