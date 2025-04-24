<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Navigation\Sidebar;
use App\Filament\Resources\PlanTierResource\Pages;
use App\Filament\Resources\PlanTierResource\RelationManagers;
use App\Models\PlanTier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanTierResource extends Resource
{
    protected static ?string $model = PlanTier::class;
    protected static ?string $navigationIcon = Sidebar::PLAN_TIER['icon'];
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
        return __("panel/plan.tier.plural");
    }

    public static function getModelLabel(): string
    {
        return __("panel/plan.tier.single");
    }

    public static function getPluralModelLabel(): string
    {
        return __("panel/plan.tier.plural");
    }
    public static function getNavigationGroup(): ?string
    {
        return __(Sidebar::PLAN_TIER['group']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__("panel/plan.tier.details"))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label(__("panel/plan.tier.title"))
                            ->translatable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(false),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__("panel/plan.tier.title")),
                Tables\Columns\TextColumn::make('plans_count')
                    ->counts('plans')
                    ->label(__("panel/plan.tier.plans_count")),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(fn() => auth()->user()->hasRole(Role::SUPER_ADMIN->value)),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanTiers::route('/'),
            'create' => Pages\CreatePlanTier::route('/create'),
            'view' => Pages\ViewPlanTier::route('/{record}'),
            'edit' => Pages\EditPlanTier::route('/{record}/edit'),
        ];
    }
}
