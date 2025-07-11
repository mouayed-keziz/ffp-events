<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\RelationManagers;

use App\Actions\ExhibitorSubmissionActions;
use App\Enums\PaymentSliceStatus;
use App\Enums\ExhibitorSubmissionStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\Currency;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
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
        return __('panel/exhibitor_submission.sections.payment_slices');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label(__('panel/exhibitor_submission.fields.payment_slice.status'))
                        ->options(PaymentSliceStatus::class)
                        ->default(PaymentSliceStatus::NOT_PAYED)
                        ->required()
                        ->columnSpan(1)
                        ->searchable(),
                    Forms\Components\TextInput::make('price')
                        ->label(__('panel/exhibitor_submission.fields.payment_slice.price'))
                        ->numeric()
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\Select::make('currency')
                        ->label(__('panel/exhibitor_submission.fields.payment_slice.currency'))
                        ->options(Currency::class)
                        ->default(Currency::DA)
                        ->required()
                        ->columnSpan(1)
                        ->searchable(),
                    Forms\Components\DatePicker::make('due_to')
                        ->label(__('panel/exhibitor_submission.fields.payment_slice.due_to'))
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
                        ->collection('attachement')
                        ->label(__('panel/exhibitor_submission.fields.payment_slice.attachment'))
                        ->downloadable()
                        ->columnSpan(2)
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        $exhibitorActions = new ExhibitorSubmissionActions();

        return $table
            ->defaultSort('created_at', 'desc')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(__('panel/exhibitor_submission.fields.payment_slice.price'))
                    ->money(fn($record) => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->badge()
                    ->color('gray')
                    ->label(__('panel/exhibitor_submission.fields.payment_slice.currency')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('panel/exhibitor_submission.fields.payment_slice.status'))
                    ->badge()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('panel/exhibitor_submission.filters.payment_slice.status'))
                    ->options(PaymentSliceStatus::class),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading(__('panel/exhibitor_submission.actions.payment_slice.create_title'))
                    ->label(__('panel/exhibitor_submission.actions.payment_slice.create_title'))
                    ->after(function ($record) {
                        // Get the parent exhibitor submission
                        $submission = $record->exhibitorSubmission;
                        // Update submission status based on payment slices
                        $submission->updateStatusBasedOnPaymentSlices();

                        Notification::make()
                            ->title(__('panel/exhibitor_submission.notifications.payment_slice_created'))
                            ->success()
                            ->send();
                    })
            ])
            ->actions([
                $exhibitorActions->getViewProofAction(),
                $exhibitorActions->getValidatePaymentAction(),
                $exhibitorActions->getRejectPaymentAction(),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                $exhibitorActions->getDeletePaymentSliceAction(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('panel/exhibitor_submission.empty.payment_slices'))
            ->emptyStateDescription(__('panel/exhibitor_submission.empty.payment_slices_description'));
    }
}
