<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportService
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    // Fungsi lama untuk API JSON
    public function getSalesReport(array $filters)
    {
        return $this->orderRepo->getReportData($filters['start_date'], $filters['end_date']);
    }

    // Fungsi baru untuk Download Excel
    public function exportSalesReport(array $filters)
    {
        $data = $this->orderRepo->getReportData($filters['start_date'], $filters['end_date']);

        $fileName = 'Sales_Report_' . $filters['start_date'] . '_to_' . $filters['end_date'] . '.xlsx';

        return Excel::download(
            new SalesReportExport($data['items_sold']),
            $fileName
        );
    }
}
