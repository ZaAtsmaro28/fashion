<?php

// app/Http/Controllers/Api/StockAdjustmentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockAdjustmentRequest;
use App\Http\Resources\StockMutationResource;
use App\Services\StockAdjustmentService;

class StockAdjustmentController extends Controller
{
    protected $adjustmentService;

    public function __construct(StockAdjustmentService $adjustmentService)
    {
        $this->adjustmentService = $adjustmentService;
    }

    public function store(StockAdjustmentRequest $request)
    {
        try {
            $result = $this->adjustmentService->adjust($request->validated());

            return (new StockMutationResource($result))
                ->additional(['message' => 'Penyesuaian stok berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
