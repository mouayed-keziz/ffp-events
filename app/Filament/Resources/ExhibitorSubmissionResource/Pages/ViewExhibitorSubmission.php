<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Actions\ExhibitorSubmissionActions;
use App\Filament\Components\ExhibitorSubmissionFormDisplay;
use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\ExhibitorResource;
use App\Filament\Resources\ExhibitorSubmissionResource;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class ViewExhibitorSubmission extends ViewRecord
{
    use NestedPage;

    protected static string $resource = ExhibitorSubmissionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        $record = $this->getRecord();
        $answers = $record->answers ?? [];
        $postAnswers = $record->post_answers ?? [];

        // Get form definitions from the event announcement's exhibitor form
        $exhibitorForm = $record->eventAnnouncement->exhibitorForm;

        // Process answers and post_answers to merge with form structure
        $processedAnswers = $this->processFormWithAnswers($exhibitorForm, $answers);
        $hasPostAnswers = !empty($postAnswers);

        // Exhibitor details components
        $exhibitorDetailsComponents = [
            TextEntry::make('exhibitor.name')
                ->label(__('panel/exhibitor_submission.details.name')),
            TextEntry::make('exhibitor.email')
                ->label(__('panel/exhibitor_submission.details.email')),
            TextEntry::make('status')
                ->label(__('panel/exhibitor_submission.details.status'))
                ->badge(),

            TextEntry::make('created_at')
                ->label(__('panel/exhibitor_submission.details.submission_date'))
                ->dateTime(),
            IconEntry::make('isEditable')
                ->label(__('panel/exhibitor_submission.details.can_edit'))
                ->boolean()
        ];

        // Price components if available
        $priceComponents = [];
        if (!empty($record->total_prices)) {
            $priceComponents[] = Fieldset::make(__('panel/exhibitor_submission.details.prices'))->columns(3)
                ->schema([
                    TextEntry::make('total_prices.DZD')
                        ->label('DZD')
                        ->formatStateUsing(fn($state) => $state ? number_format($state, 2) . ' DZD' : '-'),
                    TextEntry::make('total_prices.EUR')
                        ->label('EUR')
                        ->formatStateUsing(fn($state) => $state ? number_format($state, 2) . ' €' : '-'),
                    TextEntry::make('total_prices.USD')
                        ->label('USD')
                        ->formatStateUsing(fn($state) => $state ? number_format($state, 2) . ' $' : '-'),
                ]);
        }

        // Build the tabs array starting with exhibitor details
        $tabs = [
            Tab::make(__('panel/exhibitor_submission.tabs.exhibitor_details'))
                ->schema([
                    ...$exhibitorDetailsComponents,
                    ...$priceComponents,
                ])
                ->columns(2),
        ];

        // Add each form as its own tab
        if (!empty($processedAnswers)) {
            foreach ($processedAnswers as $formIndex => $form) {
                if (!isset($form['title']) || !isset($form['sections'])) {
                    continue;
                }

                $formTitle = is_array($form['title'])
                    ? ($form['title'][app()->getLocale()] ?? $form['title']['en'] ?? $form['title']['fr'] ?? 'Form ' . ($formIndex + 1))
                    : $form['title'];

                $tabs[] = Tab::make("form_{$formIndex}")
                    ->label($formTitle)
                    ->schema(ExhibitorSubmissionFormDisplay::make([$form]))
                    ->columns(1);
            }
        }

        // Add post-submission forms as separate tabs if available
        if ($hasPostAnswers) {
            $processedPostAnswers = $this->processFormWithAnswers($exhibitorForm, $postAnswers);

            foreach ($processedPostAnswers as $formIndex => $form) {
                if (!isset($form['title']) || !isset($form['sections'])) {
                    continue;
                }

                $formTitle = is_array($form['title'])
                    ? ($form['title'][app()->getLocale()] ?? $form['title']['en'] ?? $form['title']['fr'] ?? 'Form ' . ($formIndex + 1))
                    : $form['title'];

                $tabs[] = Tab::make("post_form_{$formIndex}")
                    ->label(__('panel/exhibitor_submission.tabs.post_form_prefix') . ": {$formTitle}")
                    ->schema(ExhibitorSubmissionFormDisplay::make([$form]))
                    ->columns(1);
            }
        }

        // Create the infolist with tabs
        return $infolist
            ->schema([
                Tabs::make('Submission Tabs')
                    ->tabs($tabs)->columnSpanFull()
            ]);
    }

    /**
     * Process form structure with answers to create a merged structure
     * 
     * @param mixed $form The form definition
     * @param array $answers The answers
     * @return array The processed form with answers
     */
    protected function processFormWithAnswers($form, array $answers): array
    {
        // If form doesn't exist or is empty, just return the answers as-is
        if (!$form || empty($form->sections)) {
            return $answers;
        }

        $sections = $form->sections ?? [];
        $result = [
            [
                'title' => $form->title ?? 'Form',
                'description' => $form->description ?? null,
                'sections' => [],
            ]
        ];

        // Deep merge the answers into the form structure
        if (!empty($sections) && !empty($answers)) {
            foreach ($sections as $sIndex => $section) {
                $result[0]['sections'][$sIndex] = $section;

                foreach ($section['fields'] ?? [] as $fIndex => $field) {
                    if (isset($answers[$sIndex]['fields'][$fIndex]['answer'])) {
                        $result[0]['sections'][$sIndex]['fields'][$fIndex]['answer'] =
                            $answers[$sIndex]['fields'][$fIndex]['answer'];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get the header actions for the page
     */
    protected function getHeaderActions(): array
    {
        $actions = new ExhibitorSubmissionActions();

        return [
            $actions->getAcceptAction(),
            $actions->getRejectAction(),
            $actions->getMakeReadyAction(),
            $actions->getArchiveAction(),
            Actions\ActionGroup::make([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
                Actions\Action::make('viewEventAnnouncement')
                    ->label(__('panel/event_announcement.resource.label'))
                    ->icon('heroicon-o-calendar')
                    ->url(fn($record) => EventAnnouncementResource::getUrl('view', ['record' => $record->eventAnnouncement]))
                    ->color('success'),
                Actions\Action::make('viewExhibitor')
                    ->label(__('panel/exhibitors.resource.single'))
                    ->icon('heroicon-o-user')
                    ->url(fn($record) => ExhibitorResource::getUrl('view', ['record' => $record->exhibitor]))
                    ->color('info'),
            ])->dropdown(true),
        ];
    }

    /**
     * Get the title for this page.
     */
    public function getTitle(): string
    {
        return __('panel/exhibitor_submission.resource.label');
    }
}
