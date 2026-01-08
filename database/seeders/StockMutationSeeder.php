<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\StockMutation;
use Illuminate\Database\Seeder;

class StockMutationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua varian yang sudah dibuat oleh ProductVariantSeeder
        $variants = ProductVariant::all();

        foreach ($variants as $variant) {
            StockMutation::create([
                'product_variant_id' => $variant->id,
                'quantity'          => $variant->stock, // Mengambil jumlah stok yang diset di ProductVariantSeeder
                'type'              => 'in',
                'reference_type'    => 'Initial Stock', // Penanda bahwa ini adalah saldo awal
                'reference_id'      => null,            // Tidak ada ID referensi karena bukan dari transaksi
                'description'       => 'Stok awal pembukaan toko',
            ]);
        }
    }
}
