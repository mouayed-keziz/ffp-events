<?php

namespace App\Filament\Resources\EventAnnouncementResource\Pages;

use App\Filament\Resources\EventAnnouncementResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Guava\FilamentNestedResources\Concerns\NestedPage;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Intervention\image;
use Illuminate\Validation\Rules\Dimensions;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EditEventAnnouncementBadgeTemplates extends EditRecord
{
    use HasPageSidebar;
    use NestedPage;

    protected static string $resource = EventAnnouncementResource::class;

    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::getUrl() => __('panel/breadcrumbs.events'),
            static::getResource()::getUrl("view", ["record" => $this->getRecord()]) => $this->getRecord()->name ?? $this->getRecord()->title,
            __('panel/breadcrumbs.update_badge_models'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('panel/event_announcement.badge_templates.section_title'))
                    ->description(__('panel/event_announcement.badge_templates.section_description'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('visitor_badge_template')
                                    ->label(__('panel/event_announcement.badge_templates.visitor_badge'))
                                    ->helperText(__('panel/event_announcement.badge_templates.aspect_ratio_hint'))
                                    ->collection('visitor_badge_template')
                                    ->image()
                                    ->imageEditor()
                                    ->imageResizeMode('cover')
                                    ->panelAspectRatio('1.4:1')
                                    ->panelLayout('integrated')
                                    // ->hint(__('panel/event_announcement.badge_templates.aspect_ratio_hint'))
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                    ->maxSize(10240) // 10MB limit
                                    ->imagePreviewHeight(250)
                                    // ->minHeight(1080)
                                    ->afterStateUpdated(function ($state) {
                                        if (!empty($state)) {
                                            Log::info('File uploaded', ['state_type' => gettype($state)]);
                                        }
                                    })
                                    ->rules([
                                        function () {
                                            return function (string $attribute, $value, \Closure $fail) {
                                                if (empty($value)) {
                                                    return;
                                                }

                                                Log::info('Validating badge template', [
                                                    'attribute' => $attribute,
                                                    'valueType' => gettype($value),
                                                ]);

                                                // Value might be TemporaryUploadedFile or array
                                                $file = $value;

                                                if (!$file || !method_exists($file, 'getRealPath') || !$file->getRealPath()) {
                                                    Log::info('Invalid file object');
                                                    return;
                                                }

                                                try {
                                                    $manager = new ImageManager(new Driver());
                                                    $image = $manager->read($file->getRealPath());
                                                    // $image = Image::make($file->getRealPath());
                                                    $width = $image->width();
                                                    $height = $image->height();
                                                    $aspectRatio = $height / $width;

                                                    Log::info('Image dimensions', [
                                                        'width' => $width,
                                                        'height' => $height,
                                                        'aspectRatio' => $aspectRatio,
                                                    ]);

                                                    if ($aspectRatio < 1.39 || $aspectRatio > 1.42) {
                                                        $fail(__('panel/event_announcement.badge_templates.aspect_ratio_error'));
                                                        return;
                                                    }

                                                    if ($height < 1080) {
                                                        $fail(__('panel/event_announcement.badge_templates.min_height_error'));
                                                        return;
                                                    }
                                                } catch (\Exception $e) {
                                                    Log::error('Failed to process image', [
                                                        'error' => $e->getMessage(),
                                                        'trace' => $e->getTraceAsString()
                                                    ]);
                                                    $fail('Failed to process image: ' . $e->getMessage());
                                                }
                                            };
                                        },
                                    ]),

                                SpatieMediaLibraryFileUpload::make('exhibitor_badge_template')
                                    ->label(__('panel/event_announcement.badge_templates.exhibitor_badge'))
                                    ->helperText(__('panel/event_announcement.badge_templates.aspect_ratio_hint'))
                                    ->collection('exhibitor_badge_template')
                                    ->image()
                                    ->imageEditor()
                                    ->imageResizeMode('cover')
                                    ->panelAspectRatio('1.4:1')
                                    ->panelLayout('integrated')
                                    // ->hint(__('panel/event_announcement.badge_templates.aspect_ratio_hint'))
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                    ->maxSize(10240) // 10MB limit
                                    ->imagePreviewHeight(250)
                                    // ->minHeight(1080)
                                    ->rules([
                                        function () {
                                            return function (string $attribute, $value, \Closure $fail) {
                                                if (empty($value)) {
                                                    return;
                                                }

                                                Log::info('Validating exhibitor badge template', [
                                                    'attribute' => $attribute,
                                                    'valueType' => gettype($value),
                                                ]);

                                                // Value might be TemporaryUploadedFile or array
                                                $file = $value;

                                                if (!$file || !method_exists($file, 'getRealPath') || !$file->getRealPath()) {
                                                    Log::info('Invalid file object');
                                                    return;
                                                }

                                                try {
                                                    $manager = new ImageManager(new Driver());
                                                    $image = $manager->read($file->getRealPath());
                                                    // $image = Image::make($file->getRealPath());
                                                    $width = $image->width();
                                                    $height = $image->height();
                                                    $aspectRatio = $height / $width;

                                                    Log::info('Image dimensions', [
                                                        'width' => $width,
                                                        'height' => $height,
                                                        'aspectRatio' => $aspectRatio,
                                                    ]);

                                                    if ($aspectRatio < 1.39 || $aspectRatio > 1.42) {
                                                        $fail(__('panel/event_announcement.badge_templates.aspect_ratio_error'));
                                                        return;
                                                    }

                                                    if ($height < 1080) {
                                                        $fail(__('panel/event_announcement.badge_templates.min_height_error'));
                                                        return;
                                                    }
                                                } catch (\Exception $e) {
                                                    Log::error('Failed to process image', [
                                                        'error' => $e->getMessage(),
                                                        'trace' => $e->getTraceAsString()
                                                    ]);
                                                    $fail('Failed to process image: ' . $e->getMessage());
                                                }
                                            };
                                        },
                                    ]),


                            ])
                    ])
            ]);
    }
}
