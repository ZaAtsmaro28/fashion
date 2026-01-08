<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll($search = null, $categoryId = null)
    {
        return Product::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('id', 'asc')
            ->paginate(10);
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    public function countAll()
    {
        return Product::count();
    }

    public function countCriticalStock($threshold)
    {
        // Menghitung produk yang total stoknya di bawah threshold
        return ProductVariant::where('stock', '<=', $threshold)->count();
    }

    public function getTopSelling($limit = 5)
    {
        // Menggunakan withSum untuk menghitung total quantity dari tabel order_items
        return Product::withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get();
    }

    public function getLowStockProducts($limit = 5)
    {
        return Product::where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->take($limit)
            ->get();
    }

    // app/Repositories/ProductRepository.php

    public function getRestockList($threshold = 5)
    {
        return ProductVariant::with(['product.category']) // Load category agar bisa difilter di frontend
            ->where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc') // Tampilkan yang paling kritis (stok 0) di paling atas
            ->get();
    }
}
