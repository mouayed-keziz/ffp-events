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

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("view", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.update_terms'),
        ];
    }

    public function form(Form $form): Form
    {
        return EventAnnouncementTermsForm::form($form);
    }
}
