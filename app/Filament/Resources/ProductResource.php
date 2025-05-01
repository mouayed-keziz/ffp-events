<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Exports\ProductExporter;
use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportBulkAction;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = Sidebar::PRODUCT['icon'];
    protected static ?int $navigationSort = Sidebar::PLAN_TIER['sort'];

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
    public static function getNavigationLabel(): string
    {
        return __("panel/product.plural");
    }
    public static function getModelLabel(): string
    {
        return __("panel/product.single");
    }

    public static function getPluralModelLabel(): string
    {
        return __("panel/product.plural");
    }
    public static function getNavigationGroup(): ?string
    {
        return __(Sidebar::PRODUCT['group']);
    }
    // public static function getModelLabel(): string
    // {
    //     return __('products/logs.resource.single');
    // }

    // public static function getPluralModelLabel(): string
    // {
    //     return __('panel/products.resource.plural');
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(5)
                    ->schema([
                        // First section: spans 3 columns
                        Grid::make(3)
                            ->schema([
                                Section::make(__("panel/product.informations"))
                                    ->columnSpan(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__("panel/product.name"))
                                            ->translatable(),
                                        TextInput::make('code')
                                            ->label(__("panel/product.code"))
                                            ->disabledOn("edit"),
                                    ]),
                                Section::make(__("panel/product.image"))
                                    ->columnSpan(1)
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('image')
                                            ->label("")
                                            ->collection('image')
                                            ->imageEditor()
                                    ])
                            ]),
                        // Second section: spans 2 columns
                        Grid::make(2)
                            ->schema([])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    // ->label(__("panel/logs.actions.export.label"))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->exporter(ProductExporter::class)
            ])
            ->columns([
                ImageColumn::make('image')
                    ->label(__("panel/product.image"))
                    ->circular(),
                TextColumn::make('name')
                    ->label(__("panel/product.name"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label(__("panel/product.code"))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color("gray"),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value))
                    ->exporter(ProductExporter::class),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value)),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value)),
                    Tables\Actions\RestoreBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value)),
                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
