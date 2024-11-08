<?php

namespace App\Providers\Filament;

use App\Commandeer\App\Pages\RegisterOrganisation;
use App\Commandeer\App\Pages\EditOrganisation;
use App\Models\Organisation;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $domain = parse_url(config('app.url'), PHP_URL_HOST);

        return $panel
            ->default()
            ->id('app')
            ->domain($domain)
            ->path('')
            ->login()
            ->registration()
            ->tenant(Organisation::class)
            ->tenantRegistration(RegisterOrganisation::class)
            ->tenantProfile(EditOrganisation::class)
            ->tenantMenuItems([
                'register' => MenuItem::make(),
            ])
            ->tenantRoutePrefix('organisation')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Commandeer/App/Resources'), for: 'App\\Commandeer\\App\\Resources')
            ->discoverPages(in: app_path('Commandeer/App/Pages'), for: 'App\\Commandeer\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Commandeer/App/Widgets'), for: 'App\\Commandeer\\App\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
