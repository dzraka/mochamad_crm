<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->colors([
                'primary' => \Filament\Support\Colors\Color::Indigo,
                'success' => \Filament\Support\Colors\Color::Emerald,
                'warning' => \Filament\Support\Colors\Color::Amber,
                'danger'  => \Filament\Support\Colors\Color::Red,
            ])
            ->font('Inter')
            ->breadcrumbs(false)
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn(): string => '<style>
                    aside.fi-sidebar {
                        background-color: #ffffff !important;
                        border-right: 1px solid #e2e8f0 !important; /* slate-200 */
                    }
                    .dark aside.fi-sidebar {
                        background-color: #000000 !important;
                        border-right: 1px solid #1e293b !important; /* slate-800 */
                    }
                </style>'
            )
            ->brandName('PT. Smart')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->sidebarCollapsibleOnDesktop()
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
