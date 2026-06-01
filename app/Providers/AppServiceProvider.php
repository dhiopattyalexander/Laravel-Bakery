<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
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
        // Share categories with navbar on every view
        View::composer('*', function ($view) {
            try {
                $navCategories = Category::orderBy('name')->get();
            } catch (\Exception $e) {
                $navCategories = collect();
            }
            $view->with('navCategories', $navCategories);
        });
    }
}
