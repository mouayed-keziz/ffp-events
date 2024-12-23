<?php

namespace App\Providers\Filament;

use App\Filament\Resources\EventAnnouncementResource\Widgets\EventAnnouncementAdvancedStats;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventAnnouncementStats;
use App\Filament\Resources\UserResource\Widgets\ExhibitorStats;
use App\Filament\Resources\VisitorResource\Widgets\UserStats;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
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
use Filament\Navigation\NavigationGroup;
use Filament\Facades\Filament;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            // ->topNavigation()
            // ->sidebarWidth('20rem')
            // ->sidebarFullyCollapsibleOnDesktop()
            // ->sidebarCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            // ->spa()
            ->login()
            ->profile()
            ->databaseNotifications()
            ->databaseTransactions()
            ->brandLogo(fn() => view('panel.brand-logo'))
            ->darkModeBrandLogo(fn() => view('panel.brand-logo'))
            ->brandLogoHeight('2rem')
            ->databaseNotificationsPolling("30s")
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->font("Inter")
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                EventAnnouncementStats::class,
                UserStats::class,
                ExhibitorStats::class,
                EventAnnouncementAdvancedStats::class,
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugins([
                // \RickDBCN\FilamentEmail\FilamentEmail::make(),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(env('APP_DEBUG', false))
                    ->users([
                        "SUPER ADMIN " => 'admin@admin.dev'
                    ])
            ]);
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->collapsible(true)
                    ->label(__('nav.groups.articles'))
                    ->icon('heroicon-o-document-duplicate'),
                NavigationGroup::make()
                    ->collapsible(true)
                    ->label(__('nav.groups.users'))
                    ->icon('heroicon-o-users'),
                NavigationGroup::make()
                    ->collapsible(true)
                    ->label(__('nav.groups.event_management'))
                    ->icon('heroicon-o-calendar'),
                NavigationGroup::make()
                    ->label(__("nav.groups.settings")),

            ]);
        });
    }
}
