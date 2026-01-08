<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Gamis Syar\'i', 'Khimar', 'Niqab', 'Abaya', 'Inner'];
        $colors = ['Olive', 'Dusty Pink', 'Charcoal', 'Milo', 'Navy'];
        $sizes = ['S', 'M', 'L', 'XL'];

        foreach ($categories as $catName) {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );

            // Buat 4 produk per kategori
            for ($i = 1; $i <= 4; $i++) {
                $productName = $catName . " " . Str::upper(Str::random(3));

                // 1. Buat Header Produk (Parent)
                $product = Product::create([
                    'category_id' => $category->id,
                    'name'        => $productName,
                    'slug'        => Str::slug($productName),
                    'sku'         => 'PROD-' . strtoupper(Str::random(6)), // Wajib ada di migrasi
                    'description' => "Koleksi premium $productName dengan bahan adem dan jahitan rapi.",
                    'price'       => rand(150000, 200000), // Kita ganti base_price jadi price
                    'stock'       => 0, // Awalnya 0, nanti bisa dijumlah dari total varian
                    'unit'        => 'pcs',
                ]);

                $totalStock = 0;

                // 2. Buat Varian untuk setiap produk
                foreach ($sizes as $size) {
                    foreach ($colors as $color) {
                        $variantStock = rand(5, 20);
                        $totalStock += $variantStock;

                        ProductVariant::create([
                            'product_id'       => $product->id,
                            'sku'              => 'SKU-' . strtoupper(Str::random(8)),
                            'size'             => $size,
                            'color'            => $color,
                            'additional_price' => ($size === 'XL') ? 15000 : 0,
                            'stock'            => $variantStock,
                        ]);
                    }
                }

                // 3. Update total stok di level produk (opsional, tergantung logika bisnis)
                $product->update(['stock' => $totalStock]);
            }
        }
    }
}
