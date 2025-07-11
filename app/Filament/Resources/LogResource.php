<?php

namespace App\Filament\Resources;

use App\Enums\LogEvent;
use App\Enums\LogName;
use App\Enums\Role;
use App\Filament\Exports\LogExporter;
use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\LogResource\Pages;
use App\Filament\Resources\LogResource\Resource\LogTable;
use App\Models\Log;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;
    protected static ?int $navigationSort = Sidebar::LOG["sort"];
    protected static ?string $navigationIcon = Sidebar::LOG["icon"];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole([Role::SUPER_ADMIN->value]);
    }

    public static function getNavigationGroup(): ?string
    {
        return __(Sidebar::LOG['group']);
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
                Forms\Components\KeyValue::make('properties')->columnSpanFull()

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
