<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RoleControlMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem; // Tambahkan import ini
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
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StaffPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('staff')
            ->path('staff')
            ->databaseNotifications()
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
                'primary' => Color::Amber,
            ])
            // Kustomisasi Auth: Menggunakan rute logout buatan sendiri
            ->authGuard('web')
            ->userMenuItems([
                'logout' => MenuItem::make()
                    ->label('Keluar Sistem')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->url(fn(): string => route('logout')),
            ])
            // Resources & Pages Staff
            ->discoverResources(in: app_path('Filament/Staff/Resources'), for: 'App\\Filament\\Staff\\Resources')
            ->discoverPages(in: app_path('Filament/Staff/Pages'), for: 'App\\Filament\\Staff\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Staff/Widgets'), for: 'App\\Filament\\Staff\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            // Middleware Standar
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
            // Middleware Proteksi
            ->authMiddleware([
                Authenticate::class,
                RoleControlMiddleware::class,
            ]);
    }
}