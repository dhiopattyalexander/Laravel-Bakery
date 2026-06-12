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
        // Global Gate Interceptor untuk Filament & Spatie
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability, $models) {
            // Jika ada parameter class/model (seperti yang dilakukan Filament)
            if (isset($models[0])) {
                $modelClass = is_string($models[0]) ? $models[0] : get_class($models[0]);
                $modelName = class_basename($modelClass);
                $modelNameSnake = \Illuminate\Support\Str::snake($modelName);

                $abilityMap = [
                    'viewAny' => 'view_any',
                    'view' => 'view',
                    'create' => 'create',
                    'update' => 'update',
                    'delete' => 'delete',
                ];

                if (isset($abilityMap[$ability])) {
                    // Cek jika modelnya adalah Spatie Role/Permission, sesuaikan namanya jika perlu
                    if ($modelNameSnake === 'permission') $modelNameSnake = 'permission';
                    if ($modelNameSnake === 'role') $modelNameSnake = 'role';

                    $permissionName = $abilityMap[$ability] . '_' . $modelNameSnake;

                    // Pastikan permission ini benar-benar ada di database, jika tidak abaikan
                    if (\Spatie\Permission\Models\Permission::where('name', $permissionName)->exists()) {
                        return $user->hasPermissionTo($permissionName) ? true : false;
                    }
                }
            }
            return null; // Jatuhkan ke default logic Laravel jika tidak ter-intercept
        });

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
