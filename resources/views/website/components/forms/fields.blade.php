@props(['fields', 'answerPath'])

<div>
    @foreach ($fields as $field)
                @switch($field['type'])
                    @case(App\Enums\FormField::INPUT->value)
                        @include('website.components.forms.input.input', [
                            'data' => $field['data'],
                            'answerPath' => $answerPath ?? null
                        ])
                    @break
                    @case(App\Enums\FormField::SELECT->value)
                        @include('website.components.forms.multiple.select', [
                            'data' => $field['data'],
                            'answerPath' => $answerPath ?? null
                        ])
                    @break
                    @case(App\Enums\FormField::CHECKBOX->value)
                        @include('website.components.forms.multiple.checkbox', [
                            'data' => $field['data'],
                            'answerPath' => $answerPath ?? null
                        ])
                    @break
                    @case(App\Enums\FormField::RADIO->value)
                        @include('website.components.forms.multiple.radio', [
                            'data' => $field['data'],
                            'answerPath' => $answerPath ?? null
                        ])
                    @break
                    @case(App\Enums\FormField::UPLOAD->value)
                        @include('website.components.forms.file-upload', [
                            'data' => $field['data'],
                            'answerPath' => $answerPath ?? null
                        ])
                    @break
                    @default
                        <div>_</div>
                @endswitch
            @endforeach
</div>
