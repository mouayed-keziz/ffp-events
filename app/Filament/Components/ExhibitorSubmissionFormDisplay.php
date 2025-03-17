<?php

namespace App\Filament\Components;

use App\Enums\FormField;
use App\Models\ExhibitorSubmission;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExhibitorSubmissionFormDisplay
{
    /**
     * Build the form display for an exhibitor submission with answers
     *
     * @param array $formData The form data with sections and answers
     * @return array Components to display in an Infolist
     */
    public static function make(array $formData): array
    {
        $sections = [];

        if (empty($formData)) {
            return $sections;
        }

        // Handle multiple forms in exhibitor submissions
        foreach ($formData as $formIndex => $form) {
            if (!isset($form['title']) || !isset($form['sections'])) {
                continue;
            }

            $formTitle = is_array($form['title'])
                ? ($form['title'][App::getLocale()] ?? $form['title']['en'] ?? $form['title']['fr'] ?? 'Form ' . ($formIndex + 1))
                : $form['title'];

            $formDescription = is_array($form['description'] ?? null)
                ? ($form['description'][App::getLocale()] ?? $form['description']['en'] ?? $form['description']['fr'] ?? '')
                : ($form['description'] ?? '');

            $formSection = InfolistSection::make($formTitle)
                ->description($formDescription)
                ->collapsible()
                ->collapsed()
                ->schema([]);

            $subSections = [];

            // Process each section within the form
            foreach ($form['sections'] as $sectionIndex => $section) {
                $sectionTitle = is_array($section['title'])
                    ? ($section['title'][App::getLocale()] ?? $section['title']['en'] ?? $section['title']['fr'] ?? 'Section ' . ($sectionIndex + 1))
                    : $section['title'];

                $sectionFields = [];

                foreach ($section['fields'] ?? [] as $fieldIndex => $field) {
                    $component = self::createFieldComponent($field);
                    if ($component) {
                        $sectionFields[] = $component;
                    }
                }

                $subSections[] = InfolistSection::make($sectionTitle)
                    ->schema($sectionFields)
                    ->collapsible()
                    ->collapsed();
            }

            // Add all subsections to the form section's schema
            $formSection->schema($subSections);
            $sections[] = $formSection;
        }

        return $sections;
    }

    /**
     * Create the appropriate component for a field
     *
     * @param array $field The field definition with type, data and answer
     * @return Components\Component|null The appropriate component for the field
     */
    protected static function createFieldComponent(array $field): ?Components\Component
    {
        $locale = App::getLocale();
        $label = $field['data']['label'][$locale] ?? $field['data']['label']['en'] ?? $field['data']['label']['fr'] ?? 'Field';
        $answer = $field['answer'] ?? null;

        return match ($field['type']) {
            FormField::INPUT->value => self::createInputComponent($field, $label, $answer),
            FormField::SELECT->value => self::createSelectComponent($field, $label, $answer),
            FormField::CHECKBOX->value => self::createCheckboxComponent($field, $label, $answer),
            FormField::RADIO->value => self::createRadioComponent($field, $label, $answer),
            FormField::UPLOAD->value => self::createUploadComponent($field, $label, $answer),
            // Handle priced fields
            FormField::SELECT_PRICED->value => self::createSelectPricedComponent($field, $label, $answer),
            FormField::CHECKBOX_PRICED->value => self::createCheckboxPricedComponent($field, $label, $answer),
            FormField::RADIO_PRICED->value => self::createRadioPricedComponent($field, $label, $answer),
            FormField::ECOMMERCE->value => self::createEcommerceComponent($field, $label, $answer),
            FormField::PLAN_TIER->value => self::createPlanTierComponent($field, $label, $answer),
            default => TextEntry::make('unknown')
                ->label($label)
                ->state('Unsupported field type: ' . $field['type'])
        };
    }

    /**
     * Create an input field component
     */
    protected static function createInputComponent(array $field, string $label, $answer): Components\Component
    {
        return TextEntry::make('input')
            ->label($label)
            ->state($answer);
    }

    /**
     * Create a select field component
     */
    protected static function createSelectComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        if (!empty($answer) && isset($answer['selected_option']['option'][$locale])) {
            $selectedOption = $answer['selected_option']['option'][$locale];
            return TextEntry::make('select')
                ->label($label)
                ->state($selectedOption);
        }

        return TextEntry::make('select')
            ->label($label)
            ->state(__('panel/visitor_submissions.no_selection'));
    }

    /**
     * Create a checkbox field component
     */
    protected static function createCheckboxComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();
        $selectedOptions = [];

        if (!empty($answer['selected_options'])) {
            foreach ($answer['selected_options'] as $selectedOption) {
                if (isset($selectedOption['option'][$locale])) {
                    $selectedOptions[] = $selectedOption['option'][$locale];
                }
            }
        }

        return TextEntry::make('checkbox')
            ->label($label)
            ->state(empty($selectedOptions) ?
                __('panel/visitor_submissions.no_selection') :
                implode(', ', $selectedOptions));
    }

    /**
     * Create a radio field component
     */
    protected static function createRadioComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        if (!empty($answer) && isset($answer['selected_option']['option'][$locale])) {
            $selectedOption = $answer['selected_option']['option'][$locale];
            return TextEntry::make('radio')
                ->label($label)
                ->state($selectedOption);
        }

        return TextEntry::make('radio')
            ->label($label)
            ->state(__('panel/visitor_submissions.no_selection'));
    }

    /**
     * Create a select priced field component
     */
    protected static function createSelectPricedComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        if (!empty($answer['options']) && !empty($answer['options'][0])) {
            $selectedOption = $answer['options'][0]['option'][$locale] ?? null;
            $price = $answer['options'][0]['price'] ?? [];

            $components = [
                TextEntry::make('select_priced_option')
                    ->label($label)
                    ->state($selectedOption ?? __('panel/visitor_submissions.no_selection'))
            ];

            if (!empty($price)) {
                $components[] = TextEntry::make('select_priced_prices')
                    ->label(__('exhibitor_submission.fields.total_prices'))
                    ->view('filament.tables.columns.prices', [
                        'state' => $price,
                    ]);
            }

            return Group::make($components);
        }

        return TextEntry::make('select_priced')
            ->label($label)
            ->state(__('panel/visitor_submissions.no_selection'));
    }

    /**
     * Create a checkbox priced field component
     */
    protected static function createCheckboxPricedComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();
        $selectedOptions = [];
        $totalPrices = [];

        if (!empty($answer['options'])) {
            foreach ($answer['options'] as $option) {
                if (isset($option['option'][$locale])) {
                    $selectedOptions[] = $option['option'][$locale];

                    // Accumulate prices for each currency
                    if (!empty($option['price'])) {
                        foreach ($option['price'] as $currency => $amount) {
                            if (!isset($totalPrices[$currency])) {
                                $totalPrices[$currency] = 0;
                            }
                            $totalPrices[$currency] += (float)$amount;
                        }
                    }
                }
            }
        }

        $components = [
            TextEntry::make('checkbox_priced_options')
                ->label($label)
                ->state(empty($selectedOptions) ?
                    __('panel/visitor_submissions.no_selection') :
                    implode(', ', $selectedOptions))
        ];

        if (!empty($totalPrices)) {
            $components[] = TextEntry::make('checkbox_priced_prices')
                ->label(__('exhibitor_submission.fields.total_prices'))
                ->view('filament.tables.columns.prices', [
                    'state' => $totalPrices,
                ]);
        }

        return Group::make($components);
    }

    /**
     * Create a radio priced field component
     */
    protected static function createRadioPricedComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        if (!empty($answer['options']) && !empty($answer['options'][0])) {
            $selectedOption = $answer['options'][0]['option'][$locale] ?? null;
            $price = $answer['options'][0]['price'] ?? [];

            $components = [
                TextEntry::make('radio_priced_option')
                    ->label($label)
                    ->state($selectedOption ?? __('panel/visitor_submissions.no_selection'))
            ];

            if (!empty($price)) {
                $components[] = TextEntry::make('radio_priced_prices')
                    ->label(__('exhibitor_submission.fields.total_prices'))
                    ->view('filament.tables.columns.prices', [
                        'state' => $price,
                    ]);
            }

            return Group::make($components);
        }

        return TextEntry::make('radio_priced')
            ->label($label)
            ->state(__('panel/visitor_submissions.no_selection'));
    }

    /**
     * Create an ecommerce field component
     */
    protected static function createEcommerceComponent(array $field, string $label, $answer): Components\Component
    {
        $components = [
            TextEntry::make('ecommerce_label')
                ->label($label)
        ];

        if (!empty($answer['products'])) {
            $products = [];
            foreach ($answer['products'] as $product) {
                $productObj = \App\Models\Product::find($product['product_id'] ?? null);
                if ($productObj) {
                    $products[] = $productObj->codename;
                }
            }

            $components[] = TextEntry::make('ecommerce_products')
                ->label(__('panel/forms.exhibitors.blocks.products'))
                ->state(implode(', ', $products));

            // Calculate total price for all products
            $totalPrices = [];
            foreach ($answer['products'] as $product) {
                if (!empty($product['price'])) {
                    foreach ($product['price'] as $currency => $amount) {
                        if (!isset($totalPrices[$currency])) {
                            $totalPrices[$currency] = 0;
                        }
                        $totalPrices[$currency] += (float)$amount;
                    }
                }
            }

            if (!empty($totalPrices)) {
                $components[] = TextEntry::make('ecommerce_prices')
                    ->label(__('exhibitor_submission.fields.total_prices'))
                    ->view('filament.tables.columns.prices', [
                        'state' => $totalPrices,
                    ]);
            }
        }

        return Group::make($components);
    }

    /**
     * Create a plan tier field component
     */
    protected static function createPlanTierComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        if (!empty($answer['selected_plan'])) {
            $planName = $answer['selected_plan']['name'][$locale] ??
                $answer['selected_plan']['name']['en'] ??
                $answer['selected_plan']['name']['fr'] ?? null;

            $components = [
                TextEntry::make('plan_tier_name')
                    ->label($label)
                    ->state($planName ?? __('panel/visitor_submissions.no_selection'))
            ];

            if (!empty($answer['selected_plan']['price'])) {
                $components[] = TextEntry::make('plan_tier_prices')
                    ->label(__('exhibitor_submission.fields.total_prices'))
                    ->view('filament.tables.columns.prices', [
                        'state' => $answer['selected_plan']['price'],
                    ]);
            }

            if (!empty($answer['selected_plan']['features'])) {
                $features = [];
                foreach ($answer['selected_plan']['features'] as $feature) {
                    $features[] = $feature[$locale] ?? $feature['en'] ?? $feature['fr'] ?? '';
                }

                $components[] = TextEntry::make('plan_tier_features')
                    ->label(__('panel/forms.exhibitors.plan_tier'))
                    ->state(implode(', ', array_filter($features)));
            }

            return Group::make($components);
        }

        return TextEntry::make('plan_tier')
            ->label($label)
            ->state(__('panel/visitor_submissions.no_selection'));
    }

    /**
     * Create an upload field component that displays files from the Media Library
     */
    protected static function createUploadComponent(array $field, string $label, $answer): Components\Component
    {
        // If no file has been uploaded (no fileId)
        if (empty($answer)) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.no_file_uploaded'));
        }

        try {
            // Get the exhibitor submission using the route parameters from the nested resource URL
            $exhibitorSubmission = null;
            $route = request()->route();

            // Try to get the exhibitor submission from the route parameters
            $exhibitorSubmissionId = $route->parameter('exhibitorSubmission') ?? $route->parameter('record');

            if ($exhibitorSubmissionId) {
                $exhibitorSubmission = ExhibitorSubmission::find($exhibitorSubmissionId);
            }

            // If we're on a page that uses Livewire and the exhibitor submission is available in the component
            if (!$exhibitorSubmission && method_exists($route->getController(), 'getRecord')) {
                $exhibitorSubmission = $route->getController()->getRecord();
            }

            // Check if we have an existing record property from a Livewire component
            if (!$exhibitorSubmission && request()->has('record') && request()->route('record') instanceof ExhibitorSubmission) {
                $exhibitorSubmission = request()->route('record');
            }

            // If we still don't have an exhibitor submission, return an error message
            if (!$exhibitorSubmission) {
                return TextEntry::make('upload')
                    ->label($label)
                    ->state(__('panel/visitor_submissions.submission_context_not_found') . ' (ID attempted: ' . $exhibitorSubmissionId . ')');
            }

            // Get the media directly from the attachments collection by fileId in custom properties
            $media = $exhibitorSubmission->getMedia('attachments')->filter(function ($media) use ($answer) {
                return isset($media->custom_properties['fileId']) && $media->custom_properties['fileId'] === $answer;
            })->first();

            if (!$media) {
                return TextEntry::make('upload')
                    ->label($label)
                    ->state(__('panel/visitor_submissions.file_not_found') . ' (FileID: ' . $answer . ')');
            }

            // Get file information
            $fileName = $media->file_name;
            $fileUrl = $media->getUrl();

            // Get the file type from custom properties (instead of relying on potentially incorrect mime_type)
            $fileType = $media->custom_properties['fieldData']['file_type'] ?? \App\Enums\FileUploadType::ANY;

            // Determine if this is an image or PDF based on the file type
            $lowerFileName = strtolower($fileName);
            $isImage = $fileType === \App\Enums\FileUploadType::IMAGE ||
                str_ends_with($lowerFileName, '.jpg') ||
                str_ends_with($lowerFileName, '.jpeg') ||
                str_ends_with($lowerFileName, '.png') ||
                str_ends_with($lowerFileName, '.gif') ||
                str_ends_with($lowerFileName, '.bmp') ||
                str_ends_with($lowerFileName, '.webp');

            $isPdf = $fileType === \App\Enums\FileUploadType::PDF || str_ends_with($lowerFileName, '.pdf');

            // Create a group with different components based on file type
            $components = [
                // Always show the file name
                TextEntry::make('file_name')
                    ->label(__('panel/visitor_submissions.file_name'))
                    ->state($fileName),
            ];

            // Add preview component based on file type
            if ($isImage) {
                $components[] = TextEntry::make('file_preview')
                    ->label(__('panel/visitor_submissions.file_preview'))
                    ->view('panel.components.visitor-submissions.image-upload', [
                        'fileName' => $fileName,
                        'fileUrl' => $fileUrl,
                    ]);
            } elseif ($isPdf) {
                // For PDFs, use the PDF Blade template
                $components[] = TextEntry::make('pdf_preview')
                    ->label(__('panel/visitor_submissions.file_preview'))
                    ->view('panel.components.visitor-submissions.pdf-upload', [
                        'fileName' => $fileName,
                        'fileUrl' => $fileUrl,
                    ]);
            } else {
                // For other file types, use the generic file template
                $components[] = TextEntry::make('file_type')
                    ->label(__('panel/visitor_submissions.file_type'))
                    ->view('panel.components.visitor-submissions.other-upload', [
                        'fileName' => $fileName,
                        'fileUrl' => $fileUrl,
                        'fileType' => $fileType,
                    ]);
            }

            // Show any additional metadata from the media
            if (isset($media->custom_properties['fieldLabel'])) {
                $locale = App::getLocale();
                $fieldLabel = $media->custom_properties['fieldLabel'][$locale] ??
                    $media->custom_properties['fieldLabel']['en'] ??
                    $media->custom_properties['fieldLabel']['fr'] ?? null;

                if ($fieldLabel) {
                    $components[] = TextEntry::make('field_label')
                        ->label(__('panel/visitor_submissions.field_label'))
                        ->state($fieldLabel);
                }
            }

            return Group::make($components)->columnSpanFull();
        } catch (\Exception $e) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.error_loading_file') . ': ' . $e->getMessage());
        }
    }
}
