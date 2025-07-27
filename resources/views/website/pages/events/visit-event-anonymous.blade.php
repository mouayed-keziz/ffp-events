@extends('website.layouts.app')

@section('content')
    @include('website.components.visit-event.banner', ['event' => $event])
    <main class="w-full max-w-5xl mx-auto px-2 md:px-4 py-8">
        <div class="-mt-32 relative z-10">
            <div class="bg-white rounded-xl shadow-lg px-4 md:px-6 py-6">
                @livewire('website.events.visit-event-anonymous', ['event' => $event])
            </div>
        </div>
    </main>
@endsection
