<?php

// app/Http/Controllers/Api/StockMutationController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetStockMutationRequest;
use App\Http\Resources\StockMutationResource;
use App\Services\StockMutationService;

class StockMutationController extends Controller
{
    protected $mutationService;

    public function __construct(StockMutationService $mutationService)
    {
        $this->mutationService = $mutationService;
    }

    public function index(GetStockMutationRequest $request)
    {
        $mutations = $this->mutationService->getHistory($request->validated());

        return StockMutationResource::collection($mutations);
    }
}
