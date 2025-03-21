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

        // Build tabs for the infolist
        $tabs = [
            Tab::make('Exhibitor Details')
                ->schema([
                    ...$exhibitorDetailsComponents,
                    ...$priceComponents,
                ])
                ->columns(2),

            Tab::make('Submission Answers')
                ->columns(1)
                ->schema(ExhibitorSubmissionFormDisplay::make($processedAnswers)),
        ];

        // Add post answers tab if available
        if ($hasPostAnswers) {
            $processedPostAnswers = $this->processFormWithAnswers($exhibitorForm, $postAnswers);
            $tabs[] = Tab::make('Post-Submission Answers')
                ->schema(ExhibitorSubmissionFormDisplay::make($processedPostAnswers));
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
            Actions\EditAction::make(),
            Actions\ActionGroup::make([
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
