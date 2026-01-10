<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\StockAdjustmentController;
use App\Http\Controllers\Api\StockMutationController;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Semua yang login bisa akses)
Route::middleware('auth:sanctum')->group(function () {

    // Logout & Profile
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::put('/profile/password', [UserController::class, 'changePassword']);


    // POS / Transaksi Kasir
    Route::post('orders', [OrderController::class, 'store']);

    Route::get('orders', [OrderController::class, 'index']); // Riwayat transaksi kasir

    Route::get('dashboard/summary', [DashboardController::class, 'index']);

    // Read-only Data (Kasir butuh lihat ini untuk jualan)
    Route::get('categories', [CategoryController::class, 'index']);

    Route::get('products', [ProductController::class, 'index']);

    Route::get('products/{product}', [ProductController::class, 'show']);

    // =========================================================
    // BOUNDARY: OWNER & GUDANG ONLY
    // =========================================================
    Route::middleware('role:owner,gudang')->group(function () {
        // Produk & Varian (Full CRUD)
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);

        Route::apiResource('variants', ProductVariantController::class)->except(['index', 'show']);

        Route::apiResource('categories', CategoryController::class)->except(['index']);

        // Inventory Management
        Route::get('inventory/low-stock', [InventoryController::class, 'restockSchedules']);

        Route::get('inventory/low-stock/export', [InventoryController::class, 'exportExcel']);

        Route::post('inventory/bulk-restock', [InventoryController::class, 'bulkUpdateStock']);

        // Stock History & Adjustment
        Route::get('stock-mutations', [StockMutationController::class, 'index']);

        Route::post('stock-adjustments', [StockAdjustmentController::class, 'store']);
    });

    // =========================================================
    // BOUNDARY: OWNER ONLY (Sensitive Data & User Management)
    // =========================================================
    Route::middleware('role:owner')->group(function () {
        // User Management
        Route::apiResource('users', UserController::class);

        // Void (Sangat sensitif, harus dengan izin owner)
        Route::post('orders/{id}/void', [OrderController::class, 'void']);

        // Financial Reports
        Route::get('reports/sales', [ReportController::class, 'salesReport']);

        Route::get('reports/sales/download', [ReportController::class, 'downloadExcel']);
    });
});
