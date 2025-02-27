@php
    $heading = __('panel/visitor_submissions.single');
@endphp

<x-filament-panels::page :heading="$heading">
    {{ $this->infolist }}
</x-filament-panels::page>