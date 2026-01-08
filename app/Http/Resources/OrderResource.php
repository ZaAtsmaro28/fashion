<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'invoice_number' => $this->invoice_number,
            'date'           => $this->created_at->format('d/m/Y H:i'),
            'cashier'        => $this->user->name ?? 'N/A',
            'items'          => $this->items->map(fn($item) => [
                'name'     => $item->product->name,
                'qty'      => $item->quantity,
                'price'    => (float) $item->price_at_sale,
                'subtotal' => (float) $item->subtotal,
            ]),
            'total_price'    => (float) $this->total_price,
            'total_pay'      => (float) $this->paid_amount,
            'total_return'   => (float) $this->change_amount,
            'footer_message' => 'Terima kasih telah berbelanja!',
        ];
    }
}
