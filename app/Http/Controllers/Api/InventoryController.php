<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BulkRestockRequest;
use App\Http\Resources\StockAlertResource;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function restockSchedules()
    {
        $items = $this->inventoryService->getRestockListData(5);

        return StockAlertResource::collection($items)
            ->additional([
                'meta' => [
                    'total_items_to_restock' => $items->count(),
                    'generated_at' => now()->toDateTimeString()
                ]
            ]);
    }

    public function bulkUpdateStock(BulkRestockRequest $request)
    {
        try {
            $this->inventoryService->handleBulkRestock($request->validated()['items']);

            return response()->json([
                'message' => 'Stok berhasil diperbarui dan mutasi telah dicatat.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function exportExcel()
    {
        // Langsung return method dari service yang mengembalikan Excel::download
        return $this->inventoryService->downloadRestockExcel();
    }
}
