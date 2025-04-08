<?php

namespace App\Filament\Pages;

use App\Activity\CompanyInformationActivity;
use App\Filament\Navigation\Sidebar;
use App\Settings\CompanyInformationsSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class ManageCompanyInformations extends SettingsPage
{
    protected static string $settings = CompanyInformationsSettings::class;
    protected static ?string $navigationIcon = Sidebar::SETTINGS["icon"];
    protected static ?int $navigationSort = Sidebar::SETTINGS["sort"];

    public function getTitle(): string
    {
        return __('settings/company_informations.title');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('settings/company_informations.description');
    }


    public static function getNavigationLabel(): string
    {
        return __('settings/company_informations.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __(Sidebar::SETTINGS["group"]);
    }

    /**
     * Called after the form is saved
     * 
     * @param array $data
     * @return void
     */
    protected function afterSave(): void
    {
        // Get the settings model
        $settings = app(CompanyInformationsSettings::class);

        // Log the activity with details about what was changed
        CompanyInformationActivity::logUpdate($this->data, $settings->name);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->persistTabInQueryString()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('settings/company_informations.tabs.general_information'))
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'lg' => 12
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('settings/company_informations.fields.name.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.name.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'lg' => 12
                                    ]),

                                Forms\Components\TextInput::make('email')
                                    ->label(__('settings/company_informations.fields.email.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.email.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                PhoneInput::make("phone")
                                    ->label(__('settings/company_informations.fields.phone.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.phone.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('country')
                                    ->label(__('settings/company_informations.fields.country.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.country.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('state')
                                    ->label(__('settings/company_informations.fields.state.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.state.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('city')
                                    ->label(__('settings/company_informations.fields.city.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.city.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('zip')
                                    ->label(__('settings/company_informations.fields.zip.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.zip.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('address')
                                    ->label(__('settings/company_informations.fields.address.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.address.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 12
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('settings/company_informations.tabs.social_links'))
                            ->schema([
                                Forms\Components\TextInput::make('facebookLink')
                                    ->label(__('settings/company_informations.fields.facebookLink.label'))
                                    ->url()
                                    ->placeholder(__('settings/company_informations.fields.facebookLink.placeholder'))
                                    ->columnSpan(12),

                                Forms\Components\TextInput::make('linkedinLink')
                                    ->label(__('settings/company_informations.fields.linkedinLink.label'))
                                    ->url()
                                    ->placeholder(__('settings/company_informations.fields.linkedinLink.placeholder'))
                                    ->columnSpan(12),

                                Forms\Components\TextInput::make('instagramLink')
                                    ->label(__('settings/company_informations.fields.instagramLink.label'))
                                    ->url()
                                    ->placeholder(__('settings/company_informations.fields.instagramLink.placeholder'))
                                    ->columnSpan(12),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('settings/company_informations.tabs.faq'))
                            ->schema([
                                Forms\Components\Repeater::make("faq")->schema([
                                    Forms\Components\TextInput::make("question")
                                        ->label(__('settings/company_informations.fields.faq.question.label'))
                                        ->required(),
                                    Forms\Components\TextInput::make("answer")
                                        ->label(__('settings/company_informations.fields.faq.answer.label'))
                                        ->required()
                                ])
                            ]),

                        Forms\Components\Tabs\Tab::make(__('settings/company_informations.tabs.terms'))
                            ->schema([
                                Forms\Components\RichEditor::make('applicationTerms')
                                    ->label(__('settings/company_informations.fields.applicationTerms.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.applicationTerms.placeholder'))
                                    ->columnSpan(12),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('settings/company_informations.tabs.company_details'))
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'lg' => 12
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('detailedAddress')
                                    ->label(__('settings/company_informations.fields.detailedAddress.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.detailedAddress.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'lg' => 12
                                    ]),

                                Forms\Components\TextInput::make('location')
                                    ->label(__('settings/company_informations.fields.location.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.location.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'lg' => 12
                                    ]),

                                Forms\Components\TextInput::make('capital')
                                    ->label(__('settings/company_informations.fields.capital.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.capital.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('rc')
                                    ->label(__('settings/company_informations.fields.rc.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.rc.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('nif')
                                    ->label(__('settings/company_informations.fields.nif.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.nif.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('ai')
                                    ->label(__('settings/company_informations.fields.ai.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.ai.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('nis')
                                    ->label(__('settings/company_informations.fields.nis.label'))
                                    ->required()
                                    ->placeholder(__('settings/company_informations.fields.nis.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),

                                Forms\Components\TextInput::make('tva')
                                    ->label(__('settings/company_informations.fields.tva.label'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix("%")
                                    ->placeholder(__('settings/company_informations.fields.tva.placeholder'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'lg' => 6
                                    ]),
                            ]),
                    ])->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 2
                    ]),
            ]);
    }
}
