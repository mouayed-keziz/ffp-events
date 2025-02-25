@extends('website.layouts.app')

@section('content')
    <main class="w-full max-w-7xl mx-auto px-4 py-8 flex-grow">
        @include('website.components.home.hero')
        @include('website.components.home.events', ['events' => $events])
        @livewire('website.events.faq')
        {{-- @livewire('website.test') --}}
    </main>
@endsection
