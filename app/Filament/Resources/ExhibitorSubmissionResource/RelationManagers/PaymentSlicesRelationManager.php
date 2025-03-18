<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\RelationManagers;

use App\Enums\PaymentSliceStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\Currency;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Carbon\Carbon;

class PaymentSlicesRelationManager extends RelationManager
{
    protected static string $relationship = 'paymentSlices';

    protected static ?string $recordTitleAttribute = 'id';
    public function isReadOnly(): bool
    {
        return false;
    }


    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('exhibitor_submission.sections.payment_slices');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label(__('exhibitor_submission.fields.payment_slice.status'))
                        ->options(PaymentSliceStatus::class)
                        ->default(PaymentSliceStatus::NOT_PAYED)
                        ->required()
                        ->columnSpan(1)
                        ->searchable(),
                    Forms\Components\TextInput::make('price')
                        ->label(__('exhibitor_submission.fields.payment_slice.price'))
                        ->numeric()
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\Select::make('currency')
                        ->label(__('exhibitor_submission.fields.payment_slice.currency'))
                        ->options(Currency::class)
                        ->default(Currency::DA)
                        ->required()
                        ->columnSpan(1)
                        ->searchable(),
                    Forms\Components\DatePicker::make('due_to')
                        ->label(__('exhibitor_submission.fields.payment_slice.due_to'))
                        ->required()
                        ->native(false)
                        ->columnSpan(1)
                        ->minDate(now()->toDateString())
                        ->afterStateHydrated(function (Forms\Components\DatePicker $component, ?string $state) {
                            if ($state) {
                                $component->state(Carbon::parse($state)->format('Y-m-d'));
                            }
                        })
                        ->displayFormat('Y-m-d'),
                    SpatieMediaLibraryFileUpload::make('attachment')
                        ->label(__('exhibitor_submission.fields.payment_slice.attachment'))
                        ->directory('payment-proofs')
                        ->visibility('private')
                        ->downloadable()
                        ->columnSpan(2)
                        ->collection('attachment'),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(__('exhibitor_submission.fields.payment_slice.price'))
                    ->money(fn($record) => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->badge()
                    ->color('gray')
                    ->label(__('exhibitor_submission.fields.payment_slice.currency')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('exhibitor_submission.fields.payment_slice.status'))
                    ->badge()
                // ->type(PaymentSliceStatus::class),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('exhibitor_submission.filters.payment_slice.status'))
                    ->options(PaymentSliceStatus::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading(__('exhibitor_submission.actions.payment_slice.create_title'))
                    ->label(__('exhibitor_submission.actions.payment_slice.create_title')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('exhibitor_submission.empty.payment_slices'))
            ->emptyStateDescription(__('exhibitor_submission.empty.payment_slices_description'));
    }
}
