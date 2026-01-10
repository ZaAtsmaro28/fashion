<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    protected $itemsSold;
    protected $startDate;
    protected $endDate;

    public function __construct($itemsSold, $startDate, $endDate)
    {
        $this->itemsSold = $itemsSold->sortByDesc('total_qty');
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->itemsSold;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN PENJUALAN PRODUK - SYARIHUB'], // Baris 1
            ['Periode: ' . $this->startDate . ' s/d ' . $this->endDate], // Baris 2
            [''], // Baris 3 (Spacer)
            [ // Baris 4 (Header Tabel)
                'ID Produk',
                'SKU',
                'Nama Produk',
                'Jumlah Terjual',
                'Total Nominal (IDR)',
            ]
        ];
    }

    public function map($item): array
    {
        return [
            $item->product_id,
            $item->product->sku,
            $item->product->name,
            $item->total_qty,
            (float) $item->total_amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp" #,##0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Perhitungan Baris Dinamis
                $headerRow = 4;
                $startDataRow = 5;
                $lastDataRow = $this->itemsSold->count() + ($startDataRow - 1);
                $totalRow = $lastDataRow + 1;

                // 1. STYLE JUDUL LAPORAN (Baris 1 & 2)
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->mergeCells('A2:E2');
                $event->sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $event->sheet->getStyle('A2')->getFont()->setSize(11)->setBold(true);

                // 2. STYLE HEADER TABEL (Baris 4) - Emerald Theme
                $event->sheet->getStyle("A{$headerRow}:E{$headerRow}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '10B981'], // Emerald Syarihub
                    ],
                ]);

                // 3. MERGE & STYLE TOTAL KESELURUHAN (Baris Bawah)
                $event->sheet->mergeCells("A{$totalRow}:C{$totalRow}");
                $event->sheet->setCellValue("A{$totalRow}", 'TOTAL KESELURUHAN');

                $styleTotal = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'], // Light Gray
                    ],
                ];
                $event->sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray($styleTotal);

                // 4. RUMUS SUM & FORMATTING
                $event->sheet->setCellValue("D{$totalRow}", "=SUM(D{$startDataRow}:D{$lastDataRow})");
                $event->sheet->setCellValue("E{$totalRow}", "=SUM(E{$startDataRow}:E{$lastDataRow})");

                // Pastikan hasil SUM di kolom E diformat Rupiah
                $event->sheet->getStyle("E{$totalRow}")->getNumberFormat()->setFormatCode('"Rp" #,##0');

                // 5. BORDER UNTUK SELURUH TABEL
                $event->sheet->getStyle("A{$headerRow}:E{$totalRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                // 6. PERATAAN TENGAH UNTUK KOLOM ID DAN SKU (Opsional agar rapi)
                $event->sheet->getStyle("A{$startDataRow}:B{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
