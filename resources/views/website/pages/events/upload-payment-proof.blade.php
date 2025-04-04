@extends('website.layouts.app')

@section('content')
    @include('website.components.exhibit-event.banner', [
        'event' => $event,
        'title' => __('website/exhibit-event.upload_payment_proof_for') . ' ' . $event->title,
        'withEndDate' => false,
    ])
    <main class="w-full max-w-5xl mx-auto px-2 md:px-4 py-8">
        <div class="-mt-52 relative z-10">
            <div class="bg-white rounded-xl shadow-lg px-4 md:px-6 py-6">
                @livewire('website.events.upload-payment-proof', ['event' => $event, 'submission' => $submission])
            </div>
        </div>
    </main>
@endsection
