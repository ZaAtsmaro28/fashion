<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductVariantRepositoryInterface;
use App\Repositories\Contracts\StockMutationRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\StockMutationRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding Category
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);

        // User
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Product
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        // Variant
        $this->app->bind(
            ProductVariantRepositoryInterface::class,
            ProductVariantRepository::class
        );
        // Order
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);

        // StockMutation
        $this->app->bind(
            StockMutationRepositoryInterface::class,
            StockMutationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
