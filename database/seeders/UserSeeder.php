<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/UserSeeder.php
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Fulanah Owner',
            'email' => 'owner@fashion.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        \App\Models\User::create([
            'name' => 'Maemunah Gudang',
            'email' => 'gudang@fashion.com',
            'password' => bcrypt('password'),
            'role' => 'gudang',
        ]);

        \App\Models\User::create([
            'name' => 'Siti Kasir',
            'email' => 'kasir@fashion.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);
    }
}
