<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Models\Visitor;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Guava\FilamentNestedResources\Pages\CreateRelatedRecord;

class CreateEventAnnouncementVisitorSubmission extends CreateRelatedRecord
{
    use NestedPage;
    // use HasPageSidebar;

    protected static string $resource = EventAnnouncementResource::class;

    protected static string $relationship = 'visitorSubmissions';

    public function getTitle(): string
    {
        return __('panel/visitor_submissions.actions.create');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Select::make('visitor_id')
                    ->label(__('panel/visitor_submissions.fields.visitor'))
                    ->options(function () {
                        return Visitor::all()->pluck('name', 'id')->toArray();
                    })
                    ->required()
                    ->searchable(),

                Components\Select::make('status')
                    ->label(__('panel/visitor_submissions.fields.status'))
                    ->options([
                        'pending' => __('panel/visitor_submissions.status.pending'),
                        'approved' => __('panel/visitor_submissions.status.approved'),
                        'rejected' => __('panel/visitor_submissions.status.rejected'),
                    ])
                    ->default('pending')
                    ->required(),

                Components\Placeholder::make('notes')
                    ->content(__('panel/visitor_submissions.notes_create'))
                    ->columnSpanFull(),
            ]);
    }
}
