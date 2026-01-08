<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_stock_mutations_table.php
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Jumlah barang (selalu positif di sini, arah ditentukan oleh 'type')
            $table->enum('type', ['in', 'out']); // 'in' untuk stok masuk, 'out' untuk stok keluar
            $table->string('reference_type'); // Contoh: 'Order', 'Adjustment', 'Restock'
            $table->unsignedBigInteger('reference_id')->nullable(); // ID dari transaksi terkait
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
