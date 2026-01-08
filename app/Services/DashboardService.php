<?php

// app/Services/DashboardService.php
namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;

class DashboardService
{
    protected $productRepo;
    protected $orderRepo;

    public function __construct(
        ProductRepositoryInterface $productRepo,
        OrderRepositoryInterface $orderRepo
    ) {
        $this->productRepo = $productRepo;
        $this->orderRepo = $orderRepo;
    }

    public function getSummary()
    {
        return [
            'total_products'       => $this->productRepo->countAll(),
            'critical_stock_count' => $this->productRepo->countCriticalStock(5),
            'today_turnover'       => $this->orderRepo->getTodayTurnover(),
            'today_transactions'   => $this->orderRepo->getTodayOrderCount(),
            'top_selling'          => $this->productRepo->getTopSelling(5)
        ];
    }
}
