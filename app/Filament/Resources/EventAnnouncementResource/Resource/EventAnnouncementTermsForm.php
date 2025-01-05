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
                        ->toolbarButtons([
                            // 'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->label(__('panel/event_announcement.fields.terms'))
                        ->columnSpanFull()
                        ->required()
                        ->translatable(),
                ])
            ]);
    }
}
