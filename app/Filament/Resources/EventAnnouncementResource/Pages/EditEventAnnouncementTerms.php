<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use App\Filament\Resources\EventAnnouncementResource\Resource\EventAnnouncementTermsForm;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class EditEventAnnouncementTerms extends EditRecord
{
    use HasPageSidebar;
    use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    public function getBreadcrumb(): string
    {
        return __('panel/event_announcement.actions.edit_terms');
    }

    public function form(Form $form): Form
    {
        return EventAnnouncementTermsForm::form($form);
    }
}
