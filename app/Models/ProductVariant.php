<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'size',
        'color',
        'additional_price',
        'stock'
    ];

    // Relasi ke produk induk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
