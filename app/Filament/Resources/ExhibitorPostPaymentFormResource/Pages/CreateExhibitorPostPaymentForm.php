<?php

namespace App\Filament\Resources\ExhibitorPostPaymentFormResource\Pages;

use App\Filament\Resources\ExhibitorPostPaymentFormResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Guava\FilamentNestedResources\Concerns\NestedPage;

class CreateExhibitorPostPaymentForm extends CreateRecord
{
    use NestedPage;
    protected static string $resource = ExhibitorPostPaymentFormResource::class;
}
