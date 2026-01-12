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
    public function getReportExportData($startDate, $endDate)
    {
        // Siapkan format tanggal yang benar
        $start = $startDate . ' 00:00:00';
        $end = $endDate . ' 23:59:59';

        // QUERY UTAMA: Hanya ambil data dari OrderItem yang punya order 'completed'
        $itemsSold = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->where('status', 'completed')
                ->whereBetween('created_at', [$start, $end]);
        })
            ->with('product:id,sku,name') // Eager loading agar tidak N+1
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_amount')
            )
            ->groupBy('product_id')
            ->get();

        return [
            'total_qty'    => (int) $itemsSold->sum('total_qty'),
            'total_amount' => (float) $itemsSold->sum('total_amount'),
            'items_sold'   => $itemsSold
        ];
    }

    public function getReportData($startDate = null, $endDate = null, $perPage = 10, $search = null)
    {
        $start = $startDate ? $startDate . ' 00:00:00' : null;
        $end = $endDate ? $endDate . ' 23:59:59' : null;

        // Base Query untuk OrderItem yang 'completed'
        $baseQuery = OrderItem::whereHas('order', function ($q) use ($start, $end) {
            $q->where('status', 'completed')
                ->when($start && $end, fn($q2) => $q2->whereBetween('created_at', [$start, $end]));
        });

        // Tambahkan Filter Search (SKU atau Nama Produk)
        $baseQuery->when($search, function ($q) use ($search) {
            $q->whereHas('product', function ($pq) use ($search) {
                $pq->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        });

        // 1. Ambil Summary (Total dari hasil yang sudah difilter search)
        // Gunakan clone() agar baseQuery tetap bisa dipakai untuk pagination
        $summary = (clone $baseQuery)->select(
            DB::raw('SUM(subtotal) as total_revenue'),
            DB::raw('COUNT(DISTINCT order_id) as total_transactions')
        )->first();

        // 2. Query untuk List Produk (dengan Pagination)
        $itemsSold = $baseQuery->with('product:id,category_id,name,sku')
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_amount')
            )
            ->groupBy('product_id')
            ->paginate($perPage);

        return [
            'total_revenue'      => (float) ($summary->total_revenue ?? 0),
            'total_transactions' => (int) ($summary->total_transactions ?? 0),
            'items_sold'         => $itemsSold
        ];
    }
}
