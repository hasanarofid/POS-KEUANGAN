<?php

namespace App\Providers\Filament;

use App\Models\Company;
use App\Models\Setting;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;
use Illuminate\Support\Facades\Vite;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->renderHook(
                'panels::head.end',
                fn (): string => '<link rel="stylesheet" href="' . Vite::asset('resources/css/filament-custom.css') . '">'
            )
            ->registration()
            ->tenant(Company::class, slugAttribute: 'slug')
            ->brandName('ArusKas')
            ->sidebarCollapsibleOnDesktop()
            ->brandLogo(function () {
                $logo = Setting::get('site_logo');
                if (!$logo)
                    return asset('images/aruskas.png');
                // Path yang dimulai 'images/' berarti ada di public langsung
                if (str_starts_with($logo, 'images/'))
                    return asset($logo);
                return asset('storage/' . $logo);
            })
            ->brandLogoHeight('2.5rem')
            ->favicon(function () {
                $favicon = Setting::get('site_favicon');
                if (!$favicon)
                    return asset('images/favicon-default.png');
                if (str_starts_with($favicon, 'images/'))
                    return asset($favicon);
                return asset('storage/' . $favicon);
            })
            ->darkMode(true)
            ->defaultThemeMode(\Filament\Enums\ThemeMode::Dark)
            ->colors([
                'primary' => Color::Orange,
                'gray' => Color::Zinc,
            ])
            ->font('Plus Jakarta Sans')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->navigationGroups([
                'Master Data',
                'Transaksi',
                'Penjualan',
                'Pembelian',
                'Produksi',
                'Laporan',
                'User Management',
                'Sistem',
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->resources([
                \App\Filament\Resources\ActivityResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
