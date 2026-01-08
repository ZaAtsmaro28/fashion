<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'status',
        'user_id',
        'customer_name',
        'total_price',
        'paid_amount',
        'change_amount',
    ];

    /**
     * Relasi ke User (Kasir yang melayani)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Detail Item (Satu order punya banyak item)
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
