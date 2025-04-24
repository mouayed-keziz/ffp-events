<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use App\Filament\Navigation\Sidebar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?int $navigationSort = Sidebar::BANNER["sort"];
    protected static ?string $navigationIcon = Sidebar::BANNER["icon"];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole(Role::SUPER_ADMIN->value);
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getNavigationGroup(): ?string
    {
        return __(Sidebar::BANNER['group']);
    }

    public static function getModelLabel(): string
    {
        return __('panel/banners.resource.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('panel/banners.resource.plural');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('panel/banners.form.sections.general'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('panel/banners.form.fields.title'))
                            ->required()
                            ->maxLength(255),

                        TextInput::make('url')
                            ->label(__('panel/banners.form.fields.url'))
                            ->url()
                            ->maxLength(255),

                        // TextInput::make('order')
                        //     ->label(__('panel/banners.form.fields.order'))
                        //     ->numeric()
                        //     ->default(0),

                        Toggle::make('is_active')
                            ->label(__('panel/banners.form.fields.is_active'))
                            ->default(true),
                    ])->columns(2),

                Section::make(__('panel/banners.form.sections.image'))
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('banner')
                            ->label(__('panel/banners.form.fields.image'))
                            ->collection('banner')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable("order")
            ->columns([
                ImageColumn::make('image')
                    ->label(__('panel/banners.table.columns.image'))
                    ->circular(),
                // ->defaultImageUrl(url('/placeholder.png')),

                TextColumn::make('title')
                    ->label(__('panel/banners.table.columns.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('url')
                    ->label(__('panel/banners.table.columns.url'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->wrap(),

                // TextColumn::make('order')
                //     ->label(__('panel/banners.table.columns.order'))
                //     ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('panel/banners.table.columns.is_active')),

                TextColumn::make('created_at')
                    ->label(__('panel/banners.table.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('panel/banners.table.columns.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('preview')
                    ->label(__('panel/banners.table.actions.preview'))
                    ->icon('heroicon-o-eye')
                    ->url(fn(Banner $record) => $record->url ? $record->url : null)
                    ->openUrlInNewTab()
                    ->visible(fn(Banner $record) => filled($record->url)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
