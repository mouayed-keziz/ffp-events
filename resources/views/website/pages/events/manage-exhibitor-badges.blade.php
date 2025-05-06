@extends('website.layouts.app')

@section('content')
    @include('website.components.exhibit-event.banner', [
        'event' => $event,
        'title' => __('website/manage-badges.page_title', ['event' => $event->title]),
        'withEndDate' => false,
    ])
    <main class="w-full max-w-5xl mx-auto px-2 md:px-4 py-8">
        <div class="-mt-40 relative z-10">
            <div class="bg-white rounded-xl shadow-lg px-4 md:px-6 py-6">
                <div>
                    @livewire('website.events.manage-exhibitor-badges', ['event' => $event, 'submission' => $submission])
                </div>
            </div>
        </div>
    </main>
@endsection
