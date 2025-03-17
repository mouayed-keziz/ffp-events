@extends('website.layouts.app')

@section('content')
    @include('website.components.visit-event.banner', ['event' => $event])
    <main class="w-full max-w-5xl mx-auto px-4 py-8">
        <div class="-mt-40 relative z-10">
            <div class="bg-white rounded-xl shadow-lg p-6">
                @livewire('website.events.view-exhibitor-submission', ['event' => $event, 'submission' => $submission])
            </div>
        </div>
    </main>
@endsection
