@extends('website.layouts.app')

@section('content')
    @include('website.components.visit-event.banner', ['event' => $event])
    <main class="w-full max-w-5xl mx-auto px-4 py-8">
        <div class="-mt-40 relative z-10">
            <div class="bg-white rounded-xl shadow-lg p-6">
                @if ($event->visitorForm)
                    @foreach ($event->visitorForm->sections as $section)
                        <div>
                            {{-- <pre>{{ json_encode($section, JSON_PRETTY_PRINT) }}</pre> --}}
                        </div>
                    @endforeach
                    @foreach ($event->visitorForm->sections as $section)
                        @include('website.components.forms.input.section_title', [
                            'title' => $section['title'][app()->getLocale()] ?? $section['title']['fr'],
                        ])
                        @foreach ($section['fields'] as $field)
                            @switch($field['type'])
                                @case(App\Enums\FormField::INPUT->value)
                                    @include('website.components.forms.input.input', [
                                        'data' => $field['data'],
                                    ])
                                @break

                                @case(App\Enums\FormField::SELECT->value)
                                    @include('website.components.forms.multiple.select', [
                                        'data' => $field['data'],
                                    ])
                                @break

                                @case(App\Enums\FormField::CHECKBOX->value)
                                    @include('website.components.forms.multiple.checkbox', [
                                        'data' => $field['data'],
                                    ])
                                @break

                                @case(App\Enums\FormField::RADIO->value)
                                    @include('website.components.forms.multiple.radio', [
                                        'data' => $field['data'],
                                    ])
                                @break

                                @case(App\Enums\FormField::UPLOAD->value)
                                    <div>{{ $field['data']['label'][app()->getLocale()] }}</div>
                                @break

                                @default
                                    <div>_</div>
                            @endswitch
                        @endforeach
                        <div class="h-8"></div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>
@endsection
