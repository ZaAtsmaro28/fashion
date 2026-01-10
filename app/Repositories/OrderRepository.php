<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data)
    {
        return Order::create($data);
    }

    public function createItem(array $data)
    {
        return OrderItem::create($data);
    }

    /**
     * Mencari Order berdasarkan ID beserta item-nya untuk proses Void
     */
    public function find($id)
    {
        return Order::with('items')->find($id);
    }

    /**
     * Hanya menghitung omzet dari transaksi yang sukses (completed)
     */
    public function getTodayTurnover()
    {
        return Order::where('status', 'completed')
            ->whereDate('created_at', now()->today())
            ->sum('total_price');
    }

    /**
     * Hanya menghitung jumlah transaksi yang sukses (completed)
     */
    public function getTodayOrderCount()
    {
        return Order::where('status', 'completed')
            ->whereDate('created_at', now()->today())
            ->count();
    }

    /**
     * Mengambil data laporan dengan mengecualikan transaksi yang dibatalkan
     */
    // public function getReportData($startDate, $endDate)
    // {
    //     // Builder dasar dengan filter status dan tanggal
    //     $orderQuery = Order::where('status', 'completed')
    //         ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

    //     return [
    //         'total_revenue'      => (float) $orderQuery->sum('total_price'),
    //         'total_transactions' => $orderQuery->count(),
    //         'items_sold'         => OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
    //             $q->where('status', 'completed')
    //                 ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
    //         })
    //             ->with('product:id,category_id,name,sku')
    //             ->select(
    //                 'product_id',
    //                 DB::raw('SUM(quantity) as total_qty'),
    //                 DB::raw('SUM(subtotal) as total_amount')
    //             )
    //             ->groupBy('product_id')
    //             ->get()
    //     ];
    // }
    public function getReportData($startDate = null, $endDate = null, $perPage = 10)
    {
        // 1. Query untuk Ringkasan (Total Revenue & Count)
        $summaryQuery = Order::where('status', 'completed')
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                return $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });

        // 2. Query untuk List Produk Terjual (dengan Pagination)
        $itemsQuery = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')
                ->when($startDate && $endDate, function ($q2) use ($startDate, $endDate) {
                    return $q2->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
                });
        })
            ->with('product:id,category_id,name,sku')
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_amount')
            )
            ->groupBy('product_id')
            ->paginate($perPage); // Tambahkan pagination di sini

        return [
            'total_revenue'      => (float) $summaryQuery->sum('total_price'),
            'total_transactions' => $summaryQuery->count(),
            'items_sold'         => $itemsQuery // Ini sekarang berisi objek LengthAwarePaginator
        ];
    }
}
