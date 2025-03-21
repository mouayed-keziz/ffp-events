<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Actions\ExhibitorSubmissionActions;
use App\Filament\Components\ExhibitorSubmissionFormDisplay;
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
                ->label('Name'),
            TextEntry::make('exhibitor.email')
                ->label('Email'),
            TextEntry::make('status')
                ->label('Status')
                ->badge(),

            TextEntry::make('created_at')
                ->label('Submission Date')
                ->dateTime(),
            IconEntry::make('isEditable')
                ->label('Can Edit')
                ->boolean()
        ];

        // Price components if available
        $priceComponents = [];
        if (!empty($record->total_prices)) {
            $priceComponents[] = Fieldset::make('Prices')->columns(3)
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
            Tab::make('Exhibitor Details')
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
                    ->label("Post: {$formTitle}")
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
            ])->dropdown(true),
        ];
    }

    /**
     * Get the title for this page.
     */
    public function getTitle(): string
    {
        return 'Exhibitor Submission';
    }
}
