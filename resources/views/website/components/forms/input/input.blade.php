@props(['data', 'answerPath' => null])

<div>
    @switch($data['type'])
        @case(App\Enums\FormInputType::TEXT->value)
            @include('website.components.forms.input.text-input', ['data' => $data, 'answerPath' => $answerPath])
        @break

        @case(App\Enums\FormInputType::EMAIL->value)
            @include('website.components.forms.input.email-input', ['data' => $data, 'answerPath' => $answerPath])
        @break

        @case(App\Enums\FormInputType::NUMBER->value)
            @include('website.components.forms.input.number-input', ['data' => $data, 'answerPath' => $answerPath])
        @break

        @case(App\Enums\FormInputType::PHONE->value)
            @include('website.components.forms.input.phone-input', ['data' => $data, 'answerPath' => $answerPath])
        @break

        @case(App\Enums\FormInputType::DATE->value)
            @include('website.components.forms.input.date-input', ['data' => $data, 'answerPath' => $answerPath])
        @break

        @case(App\Enums\FormInputType::PARAGRAPH->value)
            @include('website.components.forms.input.paragraph-input', ['data' => $data, 'answerPath' => $answerPath])
        @break

        @default
            <div>_</div>
    @endswitch
</div>
