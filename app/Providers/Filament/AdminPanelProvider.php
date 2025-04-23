<?php

namespace App\Providers\Filament;

use App\Filament\Resources\EventAnnouncementResource\Widgets\EventAnnouncementAdvancedStats;
use App\Filament\Resources\EventAnnouncementResource\Widgets\EventAnnouncementStats;
// use App\Filament\Resources\UserResource\Widgets\ExhibitorStats;
use App\Filament\Resources\VisitorResource\Widgets\UserStats;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Filament\SpatieLaravelTranslatablePlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Outerweb\FilamentTranslatableFields\Filament\Plugins\FilamentTranslatableFieldsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            // ->topNavigation()
            // ->sidebarWidth('20rem')
            ->sidebarFullyCollapsibleOnDesktop()
            // ->sidebarCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->spa(true)
            ->login()
            ->profile(isSimple: false)
            ->databaseNotifications()
            ->databaseNotificationsPolling("30s")
            ->databaseTransactions()
            ->brandLogo(fn() => view('panel.brand-logo'))
            ->darkModeBrandLogo(fn() => view('panel.brand-logo-dark'))
            ->favicon(asset(("favicon.svg")))
            ->brandLogoHeight(fn() => Auth::check() ? '3rem' : '5rem')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
                // 'primary' => Color::Orange,
            ])
            // ->font("Inter")
            ->font("Open sans")
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // EventAnnouncementStats::class,
                 UserStats::class,
                 ExhibitorStats::class,
                 EventAnnouncementAdvancedStats::class,
                Widgets\AccountWidget::class,
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
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-user'),
                \Awcodes\LightSwitch\LightSwitchPlugin::make(),
                GlobalSearchModalPlugin::make()->slideOver(false)->searchItemTree(false),
                FilamentTranslatableFieldsPlugin::make()->supportedLocales([
                    'fr' => 'Français',
                    'en' => 'English',
                    'ar' => 'العربية',
                ]),
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['fr', 'en', 'ar']),
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(config("app.debug"), true)
                    ->users([
                        "super admin " => 'admin@admin.dev',
                    ])
            ]);
    }

    // public function boot(): void
    // {
    //     Filament::serving(function () {
    //         Filament::registerNavigationGroups([
    //             NavigationGroup::make()
    //                 ->collapsible(false)
    //                 ->label(__('panel/nav.groups.event_management')),

    //             NavigationGroup::make()
    //                 ->collapsible(false)
    //                 ->label(__('panel/nav.groups.management')),

    //             NavigationGroup::make()
    //                 ->collapsible(false)
    //                 ->label(__('panel/nav.groups.articles')),

    //             NavigationGroup::make()
    //                 ->collapsible(false)
    //                 ->label(__('panel/nav.groups.users'))
    //                 ->icon('heroicon-o-users'),

    //             NavigationGroup::make()
    //                 ->collapsible(false)
    //                 ->label(__("panel/nav.groups.settings")),
    //         ]);
    //     });
    // }
}
