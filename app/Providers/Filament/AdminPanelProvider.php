<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\MenuItem;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode(false)
            ->brandLogo(fn () => view('filament.logo'))
            ->homeUrl(url('/'))
            ->brandLogoHeight('2.5rem')
            ->font('Inter')
            ->colors([
                'primary' => [
                    50 => '#fef9f0',
                    100 => '#fef3e2',
                    200 => '#fde8c8',
                    300 => '#fbd69a',
                    400 => '#f8b855',
                    500 => '#f49a21',
                    600 => '#d97706',
                    700 => '#b45309',
                    800 => '#92400e',
                    900 => '#78350f',
                    950 => '#451a03',
                ],
                'gray' => Color::Stone,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Kembali ke Toko')
                    ->url('/')
                    ->icon('heroicon-o-building-storefront'),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): string => '<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">'
            )
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): string => '
                <style>
                    /* Custom Filament Styles for Premium Theme */
                    .fi-logo {
                        font-family: "Playfair Display", serif !important;
                    }
                    .fi-topbar {
                        background: linear-gradient(135deg, #fef9f0 0%, #fef3e2 100%) !important;
                        border-bottom: 1px solid #fde8c8 !important;
                    }
                    .fi-sidebar-header {
                        background: linear-gradient(135deg, #451a03 0%, #78350f 50%, #b45309 100%) !important;
                    }
                    .fi-sidebar-header .fi-logo {
                        color: white !important;
                    }
                    .fi-sidebar {
                        background: #fef9f0 !important;
                    }
                    .fi-sidebar-item-active {
                        background: linear-gradient(135deg, #fef3e2, #fde8c8) !important;
                        color: #92400e !important;
                    }
                    /* Background page */
                    body {
                        background-color: #fbf8f3 !important;
                    }
                    .fi-main {
                        background-color: transparent !important;
                    }
                    /* Headers */
                    .fi-header-heading {
                        font-family: "Playfair Display", serif !important;
                        font-weight: 700;
                        color: #451a03 !important;
                    }
                    /* Custom Primary Button Gradients */
                    .fi-btn-color-primary, .fi-ac-btn-color-primary {
                        background: linear-gradient(135deg, #d97706, #b45309) !important;
                        border: none !important;
                        color: white !important;
                        transition: opacity 0.2s ease !important;
                    }
                    .fi-btn-color-primary:hover, .fi-ac-btn-color-primary:hover {
                        opacity: 0.9 !important;
                    }
                    /* Card headers */
                    .fi-ta-header {
                        background: linear-gradient(135deg, #fef9f0, #fef3e2) !important;
                        border-bottom: 1px solid #fde8c8 !important;
                    }
                </style>
                '
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
