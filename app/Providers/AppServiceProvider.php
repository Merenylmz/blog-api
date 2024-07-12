<?php

namespace App\Providers;

use App\Interface\BlogRepositoryInterface;
use App\Interface\CategoryRepositoryInterface;
use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
    }
}
