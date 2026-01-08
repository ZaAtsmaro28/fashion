<?php

// app/Http/Controllers/Api/ReportController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesReportRequest;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    // Endpoint JSON
    public function salesReport(SalesReportRequest $request)
    {
        $data = $this->reportService->getSalesReport($request->validated());
        return response()->json(['data' => $data]);
    }

    // Endpoint Download Excel
    public function downloadExcel(SalesReportRequest $request)
    {
        return $this->reportService->exportSalesReport($request->validated());
    }
}
