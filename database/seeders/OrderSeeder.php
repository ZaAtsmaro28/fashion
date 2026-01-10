<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 50 data order dummy
        Order::factory()->count(50)->create();
    }
}
