<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Components\VisitorSubmissionFormDisplay;
use App\Filament\Resources\EventAnnouncementResource;
use App\Models\VisitorSubmission;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\Concerns;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Filament\Notifications\Notification;

class ViewVisitorSubmission extends ViewRecord
{
    // use HasPageSidebar;
    // use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    protected static string $view = 'panel.resources.view-visitor-submission';

    /**
     * Get a fresh instance of the model represented by the resource.
     */
    protected function resolveRecord(int | string $key): Model
    {
        // Get route parameters to resolve the visitor submission
        $eventAnnouncementId = request()->route('record');
        $visitorSubmissionId = request()->route('visitorSubmission');

        // Find the visitor submission that belongs to the right event announcement
        $visitorSubmission = VisitorSubmission::query()
            ->where('id', $visitorSubmissionId)
            ->where('event_announcement_id', $eventAnnouncementId)
            ->with("badge")
            ->firstOrFail();
        $badge = $visitorSubmission->badge;

        return $visitorSubmission;
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

        // Visitor details tab components
        $visitorDetailsComponents = [
            \Filament\Infolists\Components\TextEntry::make('visitor.name')
                ->label(__('panel/visitor_submissions.fields.visitor')),
            \Filament\Infolists\Components\TextEntry::make('visitor.email')
                ->label(__('panel/visitor_submissions.fields.email', ['default' => 'Email'])),
            \Filament\Infolists\Components\TextEntry::make('status')
                ->label(__('panel/visitor_submissions.fields.status'))
                ->badge(),
            \Filament\Infolists\Components\TextEntry::make('created_at')
                ->label(__('panel/visitor_submissions.fields.created_at'))
                ->dateTime(),
        ];

        // Create the infolist schema with tabs
        return $infolist
            ->schema([
                Tabs::make('Submission Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('panel/visitor_submissions.tabs.visitor_details'))
                            ->schema($visitorDetailsComponents)
                            ->columns(1),

                        Tab::make(__('panel/visitor_submissions.tabs.submission_answers'))
                            ->schema([
                                // We'll use the FormDisplay component to render all sections
                                ...VisitorSubmissionFormDisplay::make($sections),
                            ])
                    ])
            ]);
    }

    /**
     * Get the header actions for the page
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadBadge')
                ->label(__('panel/visitor_submissions.actions.download_badge'))
                // ->icon('heroicon-o-identity')
                ->color('primary')
                ->disabled(fn() => !$this->getRecord()->badge || !$this->getRecord()->badge->getFirstMedia('image'))
                ->tooltip(fn() => !$this->getRecord()->badge
                    ? __('panel/visitor_submissions.no_badge_available')
                    : __('panel/visitor_submissions.actions.download_badge'))
                ->action(function () {
                    $badge = $this->getRecord()->badge;

                    if (!$badge) {
                        Notification::make()
                            ->title(__('panel/visitor_submissions.no_badge_available'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $mediaItem = $badge->getFirstMedia('image');

                    if (!$mediaItem) {
                        Notification::make()
                            ->title(__('panel/visitor_submissions.badge_image_not_found'))
                            ->warning()
                            ->send();

                        return;
                    }

                    return response()->download(
                        $mediaItem->getPath(),
                        $this->getRecord()->visitor->name . '-badge.png'
                    );
                }),
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
