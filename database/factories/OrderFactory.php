<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\StockMutation;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . strtoupper($this->faker->unique()->bothify('??###')),
            'user_id' => $this->faker->randomElement([1, 3]),
            'customer_name' => $this->faker->name(),
            'total_price' => 0,
            'paid_amount' => 0,
            'change_amount' => 0,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Mengambil 1-4 varian secara acak beserta data produk induknya
            $variants = ProductVariant::with('product')->inRandomOrder()->take(rand(1, 4))->get();
            $runningTotal = 0;

            foreach ($variants as $variant) {
                $qty = rand(1, 3);

                /** * LOGIKA HARGA:
                 * Harga Produk Utama + Additional Price dari Varian
                 * Kita gunakan (float) untuk memastikan angka, dan ?? 0 jika null
                 */
                $basePrice = (float) ($variant->product->price ?? 0);
                $additionalPrice = (float) ($variant->additional_price ?? 0);

                $finalPrice = $basePrice + $additionalPrice;

                $subtotal = $qty * $finalPrice;
                $runningTotal += $subtotal;

                // 2. Buat Order Item dengan harga final
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $qty,
                    'price_at_sale' => $finalPrice,
                    'subtotal' => $subtotal,
                ]);

                // 3. Buat Stock Mutation
                StockMutation::create([
                    'product_variant_id' => $variant->id,
                    'quantity' => $qty,
                    'type' => 'out',
                    'reference_type' => 'Order',
                    'reference_id' => $order->id,
                    'description' => "Penjualan Invoice: {$order->invoice_number}",
                ]);
            }

            // 4. Update Order dengan total yang sudah dihitung
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
