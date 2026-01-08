<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Http\Resources\ProductVariantResource;
use App\Services\ProductVariantService;

class ProductVariantController extends Controller
{
    protected $service;

    public function __construct(ProductVariantService $service)
    {
        $this->service = $service;
    }

    public function store(StoreProductVariantRequest $request)
    {
        $variant = $this->service->storeVariant($request->validated());
        return new ProductVariantResource($variant);
    }

    public function update(UpdateProductVariantRequest $request, $id)
    {
        $variant = $this->service->updateVariant($id, $request->validated());
        return new ProductVariantResource($variant);
    }

    public function destroy($id)
    {
        $this->service->deleteVariant($id);
        return response()->json(['message' => 'Varian berhasil dihapus']);
    }
}
