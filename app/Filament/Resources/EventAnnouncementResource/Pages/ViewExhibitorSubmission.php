<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Components\VisitorSubmissionFormDisplay;
use App\Filament\Resources\EventAnnouncementResource;
use App\Models\ExhibitorSubmission;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\Concerns;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class ViewExhibitorSubmission extends ViewRecord
{
    // use HasPageSidebar;
    // use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    protected static string $view = 'filament.resources.view-exhibitor-submission';

    /**
     * Get a fresh instance of the model represented by the resource.
     */
    protected function resolveRecord(int | string $key): Model
    {
        // Get route parameters to resolve the visitor submission
        $eventAnnouncementId = request()->route('record');
        $exhibitorSubmissionId = request()->route('exhibitorSubmission');

        // Find the visitor submission that belongs to the right event announcement
        $exhibitorSubmission = ExhibitorSubmission::query()
            ->where('id', $exhibitorSubmissionId)
            ->where('event_announcement_id', $eventAnnouncementId)
            ->firstOrFail();

        return $exhibitorSubmission;
    }

    /**
     * Configure the infolist
     *
     * @param Infolist $infolist
     * @return Infolist
     */
    public function infolist(Infolist $infolist): Infolist
    {
        $answers = $this->getRecord()->answers ?? [];

        // Get form definitions from the event announcement's visitor form
        $visitorForm = $this->getRecord()->eventAnnouncement->visitorForm;
        $sections = $visitorForm->sections ?? [];

        // Merge form definition with answers
        if (!empty($sections) && !empty($answers)) {
            // Deep merge the answers into the form sections
            // This ensures we follow the same structure but with answers included
            foreach ($sections as $sIndex => $section) {
                foreach ($section['fields'] ?? [] as $fIndex => $field) {
                    if (isset($answers[$sIndex]['fields'][$fIndex]['answer'])) {
                        $sections[$sIndex]['fields'][$fIndex]['answer'] = $answers[$sIndex]['fields'][$fIndex]['answer'];
                    }
                }
            }
        }

        // Visitor details section
        $exhibitorDetailsSection = Section::make(__('panel/visitor_submissions.visitor_details'))
            ->schema([
                \Filament\Infolists\Components\TextEntry::make('exhibitor.name')
                    ->label(__('panel/visitors.form.name')),
                \Filament\Infolists\Components\TextEntry::make('exhibitor.email')
                    ->label(__('panel/visitors.form.email')),
                \Filament\Infolists\Components\TextEntry::make('status')
                    ->label(__('panel/visitor_submissions.fields.status'))
                    ->badge(),
                \Filament\Infolists\Components\TextEntry::make('created_at')
                    ->label(__('panel/visitor_submissions.fields.created_at'))
                    ->dateTime(),
            ])
            ->columns(2);

        // Create the infolist schema
        return $infolist
            ->schema([
                $exhibitorDetailsSection,
                // Section::make("stuff")->schema([
                // ...(VisitorSubmissionFormDisplay::make($sections)),
                // ])
            ]);
    }

    /**
     * Get the header actions for the page
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Get the title for this page.
     */
    public function getTitle(): string
    {
        return __('panel/visitor_submissions.single');
    }
}
