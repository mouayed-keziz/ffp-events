<?php

namespace App\Filament\Resources\EventAnnouncementResource\Resource;

use Filament\Forms;
use Filament\Forms\Form;

class EventAnnouncementTermsForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\RichEditor::make('terms')
                        ->label("terms")
                        ->columnSpanFull()
                        ->required()
                ])
            ]);
    }
}
