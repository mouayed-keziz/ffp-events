<?php

namespace App\Filament\Pages;

use App\Settings\CompanyInformationsSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageCompanyInformations extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static string $settings = CompanyInformationsSettings::class;
    protected static ?string $navigationLabel = null;
    protected static ?string $navigationGroup = null;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('settings/company_informations.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('settings/company_informations.navigation_group');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('settings/company_informations.tabs.general_information'))
                            ->columns(12)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('settings/company_informations.fields.name.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.name.placeholder'))
                                    ->columnSpan(12),

                                Forms\Components\TextInput::make('email')
                                    ->label(__('settings/company_informations.fields.email.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.email.placeholder'))
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('phone')
                                    ->label(__('settings/company_informations.fields.phone.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.phone.placeholder'))
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('address')
                                    ->label(__('settings/company_informations.fields.address.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.address.placeholder'))
                                    ->columnSpan(12),

                                Forms\Components\TextInput::make('city')
                                    ->label(__('settings/company_informations.fields.city.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.city.placeholder'))
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('state')
                                    ->label(__('settings/company_informations.fields.state.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.state.placeholder'))
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('country')
                                    ->label(__('settings/company_informations.fields.country.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.country.placeholder'))
                                    ->columnSpan(6),

                                Forms\Components\TextInput::make('zip')
                                    ->label(__('settings/company_informations.fields.zip.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.zip.placeholder'))
                                    ->columnSpan(6)
                            ]),

                        Forms\Components\Tabs\Tab::make('Tab 2 (vide)')
                            ->schema([
                                // Reserved for future use
                            ]),

                        Forms\Components\Tabs\Tab::make('Tab 3 (vide)')
                            ->schema([
                                // Reserved for future use
                            ]),
                    ])->columnSpan(2),
            ]);
    }
}
