<?php

// app/Exports/SalesReportExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $itemsSold;

    public function __construct($itemsSold)
    {
        $this->itemsSold = $itemsSold;
    }

    /**
     * Mengambil data yang akan di-export
     */
    public function collection()
    {
        return $this->itemsSold;
    }

    /**
     * Menentukan Header/Judul Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'ID Produk',
            'Nama Produk',
            'Jumlah Terjual',
            'Total Nominal (IDR)',
        ];
    }

    /**
     * Memetakan field dari database ke kolom Excel
     */
    public function map($item): array
    {
        return [
            $item->product_id,
            $item->product->name,
            $item->total_qty,
            number_format($item->total_amount, 0, ',', '.'),
        ];
    }
}
