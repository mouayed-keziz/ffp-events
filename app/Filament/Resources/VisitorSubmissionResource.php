<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitorSubmissionResource\Pages;
use App\Models\VisitorSubmission;
use App\Models\EventAnnouncement;
use App\Models\Visitor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Guava\FilamentNestedResources\Ancestor;
use Guava\FilamentNestedResources\Concerns\NestedResource;
use App\Filament\Resources\VisitorSubmissionResource\Components\SubmissionAnswersDisplay;

class VisitorSubmissionResource extends Resource
{
    use NestedResource;

    protected static ?string $model = VisitorSubmission::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $recordTitleAttribute = 'id';
    public static ?EventAnnouncement $eventAnnouncement = null;

    public static function getEventAnnouncement(): ?EventAnnouncement
    {
        if (is_null(static::$eventAnnouncement)) {
            if ($parentId = request()->route('eventAnnouncement')) {
                static::$eventAnnouncement = EventAnnouncement::find($parentId);
            } elseif ($recordId = request()->route('record')) {
                $visitorSubmission = VisitorSubmission::find($recordId);
                if ($visitorSubmission && $visitorSubmission->eventAnnouncement) {
                    static::$eventAnnouncement = $visitorSubmission->eventAnnouncement;
                }
            }
        }
        return static::$eventAnnouncement;
    }

    public static function getAncestor(): ?Ancestor
    {
        return Ancestor::make(
            'visitorSubmissions',
            'eventAnnouncement',
        );
    }

    public static function getNavigationLabel(): string
    {
        return __("panel/visitor_submissions.plural");
    }

    public static function getModelLabel(): string
    {
        return __("panel/visitor_submissions.single");
    }

    public static function getPluralModelLabel(): string
    {
        return __("panel/visitor_submissions.plural");
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('event_announcement_id')
                    ->relationship('eventAnnouncement', 'title')
                    ->required()
                    ->disabled(static::getEventAnnouncement() !== null)
                    ->default(static::getEventAnnouncement()?->id)
                    ->label(__("panel/visitor_submissions.fields.event_announcement")),

                Forms\Components\Select::make('visitor_id')
                    ->relationship('visitor', 'email')
                    ->searchable()
                    ->preload()
                    ->label(__("panel/visitor_submissions.fields.visitor")),

                Forms\Components\TextInput::make('anonymous_email')
                    ->email()
                    ->label(__("panel/visitor_submissions.fields.anonymous_email"))
                    ->helperText(__("panel/visitor_submissions.fields.anonymous_email_help"))
                    ->visible(fn($get) => empty($get('visitor_id'))),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => __("panel/visitor_submissions.status.pending"),
                        'approved' => __("panel/visitor_submissions.status.approved"),
                        'rejected' => __("panel/visitor_submissions.status.rejected"),
                    ])
                    ->default('pending')
                    ->required()
                    ->label(__("panel/visitor_submissions.fields.status")),

                Forms\Components\Section::make(__("panel/visitor_submissions.sections.submission_answers"))
                    ->schema([
                        SubmissionAnswersDisplay::make(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.email"))
                    ->getStateUsing(fn($record) => $record->visitor ? $record->visitor->email : $record->anonymous_email)
                    ->badge()
                    ->color(fn($record) => $record->visitor ? 'success' : 'info'),
                Tables\Columns\TextColumn::make('eventAnnouncement.title')
                    ->searchable()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.event_announcement")),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->label(__("panel/visitor_submissions.fields.status")),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label(__("panel/visitor_submissions.fields.created_at")),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => __("panel/visitor_submissions.status.pending"),
                        'approved' => __("panel/visitor_submissions.status.approved"),
                        'rejected' => __("panel/visitor_submissions.status.rejected"),
                    ])
                    ->label(__("panel/visitor_submissions.fields.status")),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // 'index' => Pages\ListVisitorSubmissions::route('/'),
            // 'create' => Pages\CreateVisitorSubmission::route('/create'),
            // 'edit' => Pages\EditVisitorSubmission::route('/{record}/edit'),
        ];
    }
}
