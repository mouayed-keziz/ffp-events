@extends('website.layouts.app')

@section('title', config('app.name') . ' - ' . $event->title)

@section('meta')
    <meta name="description" content="{{ $event->description }}">
@endsection

@section('content')
    @include('website.components.event.banner', ['event' => $event])
    <div class="w-full max-w-5xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="alert bg-success text-white text-xs mt-2">
                <x-heroicon-s-information-circle class="w-7 h-7" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

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

@push('scripts')
    @if (session('badge_download_redirect'))
        <script>
            // Check for session badge download redirect and handle it
            document.addEventListener('DOMContentLoaded', function() {
                const redirectUrl = "{{ session('badge_download_redirect') }}";
                if (redirectUrl) {
                    // Set timeout to give time for the download to start
                    setTimeout(() => {
                        window.location.href = decodeURIComponent(redirectUrl);
                        // Clear the session by making an AJAX request
                        fetch('{{ route('clear.badge.redirect.session') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            credentials: 'same-origin'
                        });
                    }, 500);
                }
            });
        </script>
    @endif
@endpush
