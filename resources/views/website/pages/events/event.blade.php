@extends('website.layouts.app')

@section('title', config('app.name') . ' - ' . $event->title)

@section('meta')
    <meta name="description" content="{{ $event->description }}">
@endsection

@section('content')
    @include('website.components.event.banner', ['event' => $event])
    <div class="w-full max-w-5xl mx-auto px-4 py-8">
        @if ($exhibitorSubmission)
            @livewire('website.events.event-submission-alerts', ['event' => $event, 'submission' => $exhibitorSubmission])
            @include('website.components.event.details', [
                'event' => $event,
                'submission' => $exhibitorSubmission,
            ])
        @elseif ($visitorSubmission)
            @livewire('website.events.event-submission-alerts', ['event' => $event, 'submission' => $visitorSubmission])
            @include('website.components.event.details', [
                'event' => $event,
                'submission' => $visitorSubmission,
            ])
        @else
            @include('website.components.event.details', ['event' => $event, 'submission' => null])
        @endif
    </div>
    @if (isset($relatedEvents) && count($relatedEvents) > 0)
        <div class="w-full max-w-5xl mx-auto px-4 py-8">
            @include('website.components.home.events', ['events' => $relatedEvents])
        </div>
    @endif
    @livewire('website.empty')
@endsection
