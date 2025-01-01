<?php

namespace App\Filament\Resources;

use App\Enums\ExportType;
use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\ExportResource\Pages;
use App\Filament\Resources\ExportResource\Resource\ExportTable;
use App\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;
    protected static ?int $navigationSort = Sidebar::EXPORT["sort"];
    protected static ?string $navigationIcon = Sidebar::EXPORT["icon"];

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getModelLabel(): string
    {
        return __('panel/exports.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/exports.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('panel/nav.groups.management');
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
        return ExportTable::table($table);
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
            'index' => Pages\ListExports::route('/'),
            // 'create' => Pages\CreateExport::route('/create'),
            // 'edit' => Pages\EditExport::route('/{record}/edit'),
        ];
    }
}
