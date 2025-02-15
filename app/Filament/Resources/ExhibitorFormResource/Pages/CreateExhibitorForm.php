<?php

namespace App\Filament\Resources\ExhibitorFormResource\Pages;

use App\Filament\Resources\ExhibitorFormResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class CreateExhibitorForm extends CreateRecord
{
    use NestedPage;
    protected static string $resource = ExhibitorFormResource::class;
}
