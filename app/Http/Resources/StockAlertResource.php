<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockAlertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'variant_id'    => $this->id,
            'product_name'  => $this->product->name,
            'category'      => $this->product->category->name ?? 'Uncategorized',
            'sku'           => $this->sku,
            'current_stock' => $this->stock,
            'status'        => $this->stock <= 0 ? 'Out of Stock' : 'Low Stock',
            'suggested_qty' => 20 - $this->stock, // Contoh logika: sarankan restok sampai angka 20
        ];
    }
}
