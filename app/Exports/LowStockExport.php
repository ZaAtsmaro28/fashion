<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LowStockExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        // Kita oper data dari Repository ke sini
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    // Mengatur Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'Nama Produk',
            'SKU',
            'Kategori',
            'Stok Saat Ini',
            'Status',
            'Rekomendasi Belanja'
        ];
    }

    // Mengatur Data mana saja yang masuk ke kolom
    public function map($variant): array
    {
        return [
            $variant->product->name,
            $variant->sku,
            $variant->product->category->name ?? '-',
            $variant->stock,
            $variant->stock <= 0 ? 'Habis' : 'Menipis',
            // Logika sederhana: sarankan beli agar stok jadi 20
            max(0, 20 - $variant->stock)
        ];
    }
}
