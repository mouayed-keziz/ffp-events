<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BadgesRelationManager extends RelationManager
{
    protected static string $relationship = 'badges';
    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('website/manage-badges.team_members');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->maxLength(255)
                    ->label(__('website/manage-badges.position')),
                Forms\Components\TextInput::make('company')
                    ->required()
                    ->maxLength(255)
                    ->label(__('website/manage-badges.company')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('website/manage-badges.name')),
                Tables\Columns\TextColumn::make('email')->label(__('panel/exhibitors.columns.email')),
                Tables\Columns\TextColumn::make('position')->label(__('website/manage-badges.position')),
                Tables\Columns\TextColumn::make('company')->label(__('website/manage-badges.company')),
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('website/manage-badges.badge_image'))
                    ->getStateUsing(fn($record) => $record->getFirstMediaUrl('image'))
                    ->defaultImageUrl(url('/placeholder.png')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('downloadAll')
                    ->label(__('website/manage-badges.download_all'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($livewire) {
                        $badges = $livewire->getOwnerRecord()->badges;

                        if ($badges->isEmpty()) {
                            return;
                        }

                        $zipFileName = 'badges-' . now()->format('Y-m-d-H-i-s') . '.zip';
                        $zipPath = storage_path('app/public/' . $zipFileName);

                        $zip = new \ZipArchive();
                        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                        foreach ($badges as $badge) {
                            $mediaItem = $badge->getFirstMedia('image');
                            if ($mediaItem) {
                                $path = $mediaItem->getPath();
                                $filename = $badge->name . '-badge.png';
                                $zip->addFile($path, $filename);
                            }
                        }

                        $zip->close();

                        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('website/manage-badges.view_badge')),
                Tables\Actions\Action::make('download')
                    ->label(__('website/manage-badges.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $mediaItem = $record->getFirstMedia('image');

                        if (!$mediaItem) {
                            return;
                        }

                        return response()->download(
                            $mediaItem->getPath(),
                            $record->name . '-badge.png'
                        );
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }

}
