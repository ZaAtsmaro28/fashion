<?php

// app/Repositories/StockMutationRepository.php
namespace App\Repositories;

use App\Models\StockMutation;
use App\Repositories\Contracts\StockMutationRepositoryInterface;

class StockMutationRepository implements StockMutationRepositoryInterface
{
    public function create(array $data)
    {
        return StockMutation::create($data);
    }

    public function getFiltered(array $params)
    {
        $query = \App\Models\StockMutation::with(['variant.product']);

        return $query->when($params['product_variant_id'] ?? null, fn($q, $id) => $q->where('product_variant_id', $id))
            ->when($params['type'] ?? null, fn($q, $type) => $q->where('type', $type))
            ->when($params['start_date'] ?? null, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($params['end_date'] ?? null, fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate(15);
    }
}
