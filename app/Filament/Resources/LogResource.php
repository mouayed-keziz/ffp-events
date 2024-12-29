<?php

namespace App\Filament\Resources;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Filament\Exports\LogExporter;
use App\Filament\Resources\LogResource\Pages;
use App\Filament\Resources\LogResource\Resource\LogTable;
use App\Models\Log;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
    public static function getNavigationGroup(): ?string
    {
        return __('panel/nav.groups.management');
    }
    public static function getModelLabel(): string
    {
        return __('panel/logs.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/logs.resource.plural');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return LogTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route('/'),
            // 'create' => Pages\CreateLog::route('/create'),
            // 'view' => Pages\ViewLog::route('/{record}'),
            // 'edit' => Pages\EditLog::route('/{record}/edit'),
        ];
    }
}
