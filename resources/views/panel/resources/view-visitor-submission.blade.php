@php
    $heading = __('panel/visitor_submissions.single');
@endphp
<x-filament-panels::page :heading="$heading">
    {{ $this->infolist }}
    {{-- <div data-theme="dark">
        @include('website.components.forms.debug-path', [
            'title' => 'submission details',
            'data' => $this->record->answers,
        ])
    </div> --}}
</x-filament-panels::page>
