<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(Request $request)
    {
        try {
            $order = $this->orderService->checkout($request->all());
            return response()->json([
                'message' => 'Transaksi Berhasil!',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // app/Http/Controllers/Api/OrderController.php

    public function void($id)
    {
        try {
            $order = $this->orderService->voidOrder($id);
            return response()->json([
                'message' => 'Transaksi berhasil dibatalkan, stok telah dikembalikan.',
                'data' => [
                    'invoice_number' => $order->invoice_number,
                    'status' => $order->status
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
