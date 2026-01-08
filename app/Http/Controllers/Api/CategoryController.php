<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $categories = $this->service->getCategories($request->search);
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = $this->service->storeCategory($request->validated());
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = $this->service->updateCategory($id, $request->validated());
        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        $this->service->deleteCategory($id);
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
