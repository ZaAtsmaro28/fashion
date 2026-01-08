<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Exports\LowStockExport;
use Maatwebsite\Excel\Facades\Excel;

class InventoryService
{
    protected $productRepo;
    protected $variantService;

    public function __construct(
        ProductRepositoryInterface $productRepo,
        ProductVariantService $variantService
    ) {
        $this->productRepo = $productRepo;
        $this->variantService = $variantService;
    }

    /**
     * Mengambil data stok rendah untuk tampilan list
     */
    public function getRestockListData($threshold = 5)
    {
        return $this->productRepo->getRestockList($threshold);
    }

    /**
     * Memproses update stok masal melalui VariantService
     */
    public function handleBulkRestock(array $items)
    {
        return $this->variantService->bulkRestock($items);
    }

    /**
     * Logika persiapan file Excel
     */
    public function downloadRestockExcel()
    {
        $data = $this->getRestockListData(5);
        $fileName = 'Daftar_Belanja_Restok_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new LowStockExport($data), $fileName);
    }
}
