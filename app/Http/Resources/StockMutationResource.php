<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMutationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'variant'        => [
                'id'   => $this->variant->id,
                'sku'  => $this->variant->sku,
                'name' => $this->variant->product->name . ' - ' . $this->variant->name,
            ],
            'quantity'       => $this->quantity,
            'type'           => $this->type,
            'reference_type' => $this->reference_type,
            'reference_id'   => $this->reference_id,
            'description'    => $this->description,
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
