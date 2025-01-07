<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Models\ExhibitorForm;
use App\Models\EventAnnouncement;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManageEventAnnouncementExhibitorForms extends ListRecords
{
    use HasPageSidebar;
    use ListRecords\Concerns\Translatable;

    public ?EventAnnouncement $record = null;

    protected static string $resource = EventAnnouncementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\Action::make('create')
                ->url(fn(): string => EventAnnouncementResource::getUrl('create-exhibitor-form', ['record' => $this->record]))
                ->icon('heroicon-o-plus'),
        ];
    }

    public function mount(): void
    {
        $this->record = EventAnnouncement::find(request()->route('record'))->first();
    }

    protected function getTableQuery(): Builder
    {
        return ExhibitorForm::query()->where('event_announcement_id', $this->record->id);
    }

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => static::getResource()::getModelLabel(),
            static::getResource()::getUrl('view', ['record' => $this->record]) => $this->record->title,
            '#' => __('panel/exhibitors.resource.plural'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn(ExhibitorForm $record): string =>
                    EventAnnouncementResource::getUrl('edit-exhibitor-form', [
                        'record' => $this->record,
                        'exhibitorForm' => $record
                    ]))
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
