<?php

namespace App\Filament\Pages;

use App\Enums\CheckInOutAction;
use App\Enums\Role;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class QrScannerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static string $view = 'panel.pages.qr-scanner-page';
    // sort
    protected static ?int $navigationSort = 100;

    public static function getNavigationLabel(): string
    {
        return __('panel/scanner.navigation_label');
    }

    public function getTitle(): string
    {
        return __('panel/scanner.title');
    }

    public function getHeader(): View
    {
        return view('panel.pages.components.scanner-header');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole([Role::HOSTESS->value]);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return Auth::user()->hasRole([Role::HOSTESS->value]);
    }
}
