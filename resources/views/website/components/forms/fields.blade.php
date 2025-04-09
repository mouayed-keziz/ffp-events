@props(['fields', 'answerPath', 'disabled'])

<div>
    @foreach ($fields as $field)
        @switch($field['type'])
            @case(App\Enums\FormField::INPUT->value)
                @include('website.components.forms.input.input', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::SELECT->value)
                @include('website.components.forms.multiple.select', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::CHECKBOX->value)
                @include('website.components.forms.multiple.checkbox', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::RADIO->value)
                @include('website.components.forms.multiple.radio', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::UPLOAD->value)
                @include('website.components.forms.file-upload', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            {{-- Priced field types --}}
            @case(App\Enums\FormField::SELECT_PRICED->value)
                @include('website.components.forms.priced.select', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::CHECKBOX_PRICED->value)
                @include('website.components.forms.priced.checkbox', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::RADIO_PRICED->value)
                @include('website.components.forms.priced.radio', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::ECOMMERCE->value)
                @include('website.components.forms.priced.ecommerce', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::PLAN_TIER->value)
                @include('website.components.forms.priced.plan_tier', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @case(App\Enums\FormField::PLAN_TIER_CHECKBOX->value)
                @include('website.components.forms.priced.plan_tier_checkbox', [
                    'data' => $field['data'],
                    'answerPath' => $answerPath ?? null,
                    'disabled' => $disabled ?? false,
                ])
            @break

            @default
                <div>_</div>
        @endswitch
    @endforeach
</div>
