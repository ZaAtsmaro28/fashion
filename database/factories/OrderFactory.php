<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\StockMutation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        // 1. SET RENTANG TANGGAL: Januari 2026 (Tanggal 1 sampai 31)
        $randomDate = $this->faker->dateTimeBetween('2026-01-01', '2026-01-31');

        return [
            // Gunakan $randomDate agar invoice sesuai dengan tanggal transaksi
            'invoice_number' => 'INV-' . $randomDate->format('Ymd') . '-' . strtoupper($this->faker->unique()->bothify('??###')),
            'user_id' => $this->faker->randomElement([1, 3]),
            'customer_name' => $this->faker->name(),
            'total_price' => 0,
            'paid_amount' => 0,
            'change_amount' => 0,
            'status' => 'completed',

            // Set created_at dan updated_at manual agar tidak menggunakan waktu sekarang
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Tangkap tanggal dari order agar item dan mutasi stock SINKRON
            $orderDate = $order->created_at;

            $variants = ProductVariant::with('product')->inRandomOrder()->take(rand(1, 4))->get();
            $runningTotal = 0;

            foreach ($variants as $variant) {
                $qty = rand(1, 3);
                $basePrice = (float) ($variant->product->price ?? 0);
                $additionalPrice = (float) ($variant->additional_price ?? 0);
                $finalPrice = $basePrice + $additionalPrice;
                $subtotal = $qty * $finalPrice;
                $runningTotal += $subtotal;

                // OrderItem menggunakan tanggal yang sama dengan Order
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $qty,
                    'price_at_sale' => $finalPrice,
                    'subtotal' => $subtotal,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                // StockMutation menggunakan tanggal yang sama dengan Order
                StockMutation::create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $qty,
                    'type' => 'out',
                    'reference_type' => 'Order',
                    'reference_id' => $order->id,
                    'description' => "Penjualan Invoice: {$order->invoice_number}",
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);
            }

            $paidAmount = ceil($runningTotal / 10000) * 10000;
            if ($paidAmount < $runningTotal) $paidAmount += 10000;

            $order->update([
                'total_price' => $runningTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => $paidAmount - $runningTotal,
            ]);
        });
    }
}
