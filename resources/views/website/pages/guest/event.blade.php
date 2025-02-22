@extends('website.layouts.app')

@section('content')
    @include('website.components.event.banner', ['event' => $event])
    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        @include('website.components.event.details', ['event' => $event])
    </div>
    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        @include('website.components.home.events', ['events' => $relatedEvents])
    </div>
@endsection

@livewireScriptConfig
