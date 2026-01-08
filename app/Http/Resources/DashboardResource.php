<?php

// app/Http/Resources/DashboardResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'statistics' => [
                'total_products'   => $this['total_products'],
                'critical_stock_count' => $this['critical_stock_count'],
                'today_turnover'   => (float) $this['today_turnover'],
                'today_transactions' => $this['today_transactions'],
            ],
            'top_selling_products' => $this['top_selling']->map(fn($p) => [
                'name' => $p->name,
                'sold' => $p->total_sold ?? 0,
            ]),
            'server_time' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
