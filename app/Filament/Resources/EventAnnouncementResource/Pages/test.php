<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use App\Enums\ExhibitorFormFieldType;
use Filament\Forms;
use Filament\Forms\Form;

class ExhibitorFormDefinition
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Translatable Title
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->translatable()
                    ->columnSpan(2),

                // Currencies (multi-select)
                Forms\Components\Select::make('currencies')
                    ->label('Currencies')
                    ->options([
                        'DZD' => 'DZD',
                        'EUR' => 'EUR',
                        'USD' => 'USD',
                    ])
                    ->multiple()
                    ->native(false)
                    ->columnSpanFull()
                    ->required()
                    ->live()
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('setStaticPrices')
                            ->label('Set Static Prices')
                            ->icon('heroicon-o-plus')
                            ->action(function ($set, $get) {
                                // Set static prices for the KeyValue inputs inside the repeaters
                                $staticPrices = [
                                    'EUR' => 100,
                                    'USD' => 120,
                                ];

                                // Update prices for each field in the fields repeater
                                $fields = $get('fields');
                                if (is_array($fields)) {
                                    foreach ($fields as $index => $field) {
                                        $set("fields.{$index}.prices", $staticPrices);
                                    }
                                }

                                // Update prices for each option in the options repeater
                                if (is_array($fields)) {
                                    foreach ($fields as $fieldIndex => $field) {
                                        $options = $field['options'] ?? [];
                                        if (is_array($options)) {
                                            foreach ($options as $optionIndex => $option) {
                                                $set("fields.{$fieldIndex}.options.{$optionIndex}.prices", $staticPrices);
                                            }
                                        }
                                    }
                                }
                            })
                    )
                    ->afterStateUpdated(function ($state, $set, $get) {
                        // Sync prices for all fields and options when currencies are updated
                        self::syncPricesWithCurrencies($state, $set, $get);
                    }),

                // Fields Repeater
                Forms\Components\Repeater::make('fields')
                    ->addActionLabel('Add Field')
                    ->collapsible()
                    ->collapsed(true)
                    ->itemLabel(fn($state) => self::getFieldLabel($state))
                    ->label('Fields')
                    ->schema([
                        // Field Label and Description
                        Forms\Components\Group::make()->columns(2)->schema([
                            Forms\Components\TextInput::make('label')
                                ->label('Label')
                                ->required()
                                ->translatable(),
                            Forms\Components\TextInput::make('description')
                                ->label('Description')
                                ->translatable(),
                        ]),

                        // Field Type and Required Toggle
                        Forms\Components\Group::make()->columns(10)->schema([
                            Forms\Components\Select::make('type')
                                ->label('Type')
                                ->default(ExhibitorFormFieldType::Text->value)
                                ->native(false)
                                ->options(ExhibitorFormFieldType::class)
                                ->required()
                                ->live()
                                ->columnSpan(8),
                            Forms\Components\Toggle::make('required')
                                ->label('Required')
                                ->default(true)
                                ->inline(false)
                                ->columnSpan(1),
                        ]),

                        // Toggle to enable/disable pricing
                        Forms\Components\Toggle::make('enable_pricing')
                            ->label('Enable Pricing')
                            ->live()
                            ->columnSpanFull()
                            ->hidden(fn($get) => !self::shouldShowPricingToggle($get)),

                        // Key-Value Input for Single Price Input
                        Forms\Components\KeyValue::make('prices') // Changed from single_prices to prices
                            ->label('Prices')
                            ->keyLabel('Currency')
                            ->valueLabel('Price')
                            ->addable(false)
                            ->deletable(false)
                            ->editableKeys(false)
                            ->default([])
                            ->afterStateUpdated(function ($state, $set) {
                                foreach ($state as $currency => $price) {
                                    if (!is_numeric($price)) {
                                        $set('prices.' . $currency, null);
                                    }
                                }
                            })
                            ->columnSpanFull()
                            ->hidden(fn($get) => !self::shouldShowSinglePriceInput($get))
                            ->live(),

                        // Options Repeater (for fields with options)
                        Forms\Components\Repeater::make('options')
                            ->addActionLabel('Add Option')
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn($state) => self::getOptionLabel($state))
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->label('Option')
                                    ->required()
                                    ->translatable(),

                                // Key-Value Input for Multiple Price Inputs
                                Forms\Components\KeyValue::make('prices')
                                    ->label('Prices')
                                    ->keyLabel('Currency')
                                    ->valueLabel('Price')
                                    ->addable(false)
                                    ->deletable(false)
                                    ->editableKeys(false)
                                    ->default(fn($get) => self::getDefaultPrices($get('../../currencies'))) // Initialize with selected currencies
                                    ->afterStateUpdated(function ($state, $set) {
                                        foreach ($state as $currency => $price) {
                                            if (!is_numeric($price)) {
                                                $set('prices.' . $currency, null);
                                            }
                                        }
                                    })
                                    ->columnSpanFull()
                                    ->hidden(fn($get) => !self::shouldShowMultiplePriceInput($get))
                                    ->live(),
                            ])
                            ->columnSpanFull()
                            ->hidden(fn($get) => !self::shouldShowOptionsRepeater($get)),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Sync prices for all fields and options when currencies are updated.
     */
    protected static function syncPricesWithCurrencies(?array $currencies, $set, $get): void
    {
        // Update prices for each field in the fields repeater
        $fields = $get('fields');
        if (is_array($fields)) {
            foreach ($fields as $index => $field) {
                $set("fields.{$index}.prices", self::getDefaultPrices($currencies));
            }
        }

        // Update prices for each option in the options repeater
        if (is_array($fields)) {
            foreach ($fields as $fieldIndex => $field) {
                $options = $field['options'] ?? [];
                if (is_array($options)) {
                    foreach ($options as $optionIndex => $option) {
                        $set("fields.{$fieldIndex}.options.{$optionIndex}.prices", self::getDefaultPrices($currencies));
                    }
                }
            }
        }
    }

    protected static function getFieldLabel(array $state): string
    {
        $label = is_array($state['label'])
            ? ($state['label'][app()->getLocale()] ?? array_values($state['label'])[0])
            : $state['label'];

        if ($state['type'] && ExhibitorFormFieldType::from($state['type'])) {
            return ExhibitorFormFieldType::from($state['type'])->getLabel() . ' - ' . $label;
        }

        return $label;
    }

    protected static function getOptionLabel(array $state): string
    {
        $label = is_array($state['value'])
            ? ($state['value'][app()->getLocale()] ?? array_values($state['value'])[0])
            : $state['value'];

        return $label ?? 'Option';
    }

    protected static function shouldShowPricingToggle($get): bool
    {
        $type = $get('type');
        if (!$type) {
            return false;
        }

        $fieldType = ExhibitorFormFieldType::from($type);
        return $fieldType->hasSinglePriceInput() || $fieldType->hasMultiplePriceInput();
    }

    protected static function shouldShowSinglePriceInput($get): bool
    {
        $type = $get('type');
        if (!$type) {
            return false;
        }

        $fieldType = ExhibitorFormFieldType::from($type);
        return $fieldType->hasSinglePriceInput() && $get('enable_pricing');
    }

    protected static function shouldShowMultiplePriceInput($get): bool
    {
        $type = $get('../../type');
        $enablePricing = $get('../../enable_pricing');

        if (!$type) {
            return false;
        }

        $fieldType = ExhibitorFormFieldType::from($type);
        return $fieldType->hasMultiplePriceInput() && $enablePricing;
    }

    protected static function shouldShowOptionsRepeater($get): bool
    {
        $type = $get('type');
        if (!$type) {
            return false;
        }

        $fieldType = ExhibitorFormFieldType::from($type);
        return $fieldType->hasOptions();
    }

    protected static function getDefaultPrices(?array $currencies): array
    {
        if (empty($currencies)) {
            return [];
        }

        return array_fill_keys($currencies, null);
    }

    public static function cleanUpFormData(array $data): array
    {
        // Clean up fields data
        $data['fields'] = collect($data['fields'])->map(function ($field) {
            $fieldType = ExhibitorFormFieldType::from($field['type']);

            // Remove options if the field type doesn't have options
            if (!$fieldType->hasOptions()) {
                unset($field['options']);
            }

            // Remove pricing data if pricing is not enabled
            if (!($field['enable_pricing'] ?? false)) {
                unset($field['prices']);
            }

            return $field;
        })->toArray();

        // Remove currencies if no fields require pricing
        $hasPricing = collect($data['fields'])->contains(function ($field) {
            return $field['enable_pricing'] ?? false;
        });

        if (!$hasPricing) {
            unset($data['currencies']);
        }

        return $data;
    }
}
