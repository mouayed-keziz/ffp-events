@extends('website.layouts.app')

@section('content')
    @include('website.components.visit-event.banner', ['event' => $event])
    <main class="w-full max-w-5xl mx-auto px-4 py-8">
        <div class="-mt-40 relative z-10">
            @livewire('website.events.visit-event', ['event' => $event])
        </div>
    </main>
@endsection
