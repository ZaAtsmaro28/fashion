<?php

// app/Http/Resources/SalesReportResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'period' => [
                'start' => $request->start_date,
                'end'   => $request->end_date,
            ],
            'summary' => [
                'total_revenue'      => (float) $this['total_revenue'],
                'total_transactions' => $this['total_transactions'],
                'average_per_order'  => $this['total_transactions'] > 0
                    ? $this['total_revenue'] / $this['total_transactions']
                    : 0,
            ],
            'items_sold' => $this['items_sold'], // Daftar produk yang terjual di periode ini
        ];
    }
}
