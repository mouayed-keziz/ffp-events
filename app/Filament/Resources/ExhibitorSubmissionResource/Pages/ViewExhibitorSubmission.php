<?php

namespace App\Filament\Resources\ExhibitorSubmissionResource\Pages;

use App\Filament\Resources\ExhibitorSubmissionResource;
use Filament\Actions;
use Filament\Infolists\Components\Fieldset;
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
        $exhibitorDetailsSection = [
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
            \Filament\Infolists\Components\IconEntry::make("isEditable")->boolean()
        ];

        // Create the infolist schema
        return $infolist
            ->schema([
                Tabs::make("tabs")->columnSpanFull()
                    ->schema([
                        Tab::make("details")
                            ->columns(2)
                            ->schema([
                                ...$exhibitorDetailsSection
                            ]),
                        Tab::make("answers")
                            ->schema([
                                Fieldset::make("total_prices")->columns(3)
                                    ->schema([
                                        TextEntry::make("total_prices.DZD")->label("DZD"),
                                        TextEntry::make("total_prices.EUR")->label("EUR"),
                                        TextEntry::make("total_prices.USD")->label("USD"),
                                    ])
                            ])
                    ])
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
            Actions\ActionGroup::make([]),
            Actions\EditAction::make(),
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
