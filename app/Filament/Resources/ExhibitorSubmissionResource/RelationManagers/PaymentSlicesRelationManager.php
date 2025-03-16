<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\RelationManagers;

use App\Enums\PaymentSliceStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentSlicesRelationManager extends RelationManager
{
    protected static string $relationship = 'paymentSlices';

    protected static ?string $recordTitleAttribute = 'id';

    // public static function getTitle(): string
    // {
    //     return __('exhibitor_submission.sections.payment_slices');
    // }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->label(__('exhibitor_submission.fields.payment_slice.status'))
                ->options(PaymentSliceStatus::class)
                ->required(),
            Forms\Components\TextInput::make('price')
                ->label(__('exhibitor_submission.fields.payment_slice.price'))
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('sort')
                ->label(__('exhibitor_submission.fields.payment_slice.sort'))
                ->numeric()
                ->required(),
            Forms\Components\Select::make('currency')
                ->label(__('exhibitor_submission.fields.payment_slice.currency'))
                ->options([
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                ])
                ->required(),
            Forms\Components\FileUpload::make('attachment')
                ->label(__('exhibitor_submission.fields.payment_slice.attachment'))
                ->directory('payment-proofs')
                ->visibility('private')
                ->downloadable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort')
                    ->label(__('exhibitor_submission.fields.payment_slice.sort'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('exhibitor_submission.fields.payment_slice.price'))
                    ->money(fn($record) => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
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
                    ->label(__('exhibitor_submission.actions.payment_slice.create')),
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
            ]);
    }
}
