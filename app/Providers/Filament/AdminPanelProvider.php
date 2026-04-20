<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\ActivityLogWidget;
use App\Http\Middleware\RoleControlMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->databaseNotifications()
            // LOGO & BRANDING
            ->brandLogo(new HtmlString('
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <img 
                        src="' . asset('images/logo.png') . '" 
                        alt="SIKEDIP Logo" 
                        style="height: 2.5rem; width: auto;"
                    > 
                    <span style="font-size: 1.25rem; font-weight: 600; color: #1f2937;">
                        SIKEDIP 
                    </span>
                </div>
            '))
            ->colors([
                'primary' => Color::Green,
            ])
            ->authGuard('web')
            ->userMenuItems([
                'logout' => MenuItem::make()
                    ->label('Keluar')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->url(fn(): string => route('logout')), // Mengarah ke route logout di LoginController
            ])
            // DISCOVERIES
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                ActivityLogWidget::class,
            ])
            // MIDDLEWARE
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
            ->authMiddleware([
                Authenticate::class,
                RoleControlMiddleware::class, // Menangani pembatasan role & redirect antar panel
            ]);
    }
}