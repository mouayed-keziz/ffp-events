@extends('website.layouts.app')

@section('content')
    @include('website.components.visit-event.banner', ['event' => $event])
    <main class="w-full max-w-5xl mx-auto px-4 py-8">
        <div class="-mt-40 relative z-10">
            <div class="bg-white rounded-xl shadow-lg px-6">
                <div class="flex flex-col items-center justify-center py-4">
                    <div class="h-64">
                        <img src="{{ asset('rocket.png') }}" alt="Success" class="w-full h-full">
                    </div>

                    <h2 class="text-xl font-bold mt-6 text-center">
                        {{ __('website/visit-event.anonymous_registration_success') }}
                    </h2>

                    <p class="text-gray-400 text-sm mt-4 text-center max-w-3xl">
                        {{ __('website/visit-event.anonymous_registration_success_message') }}
                    </p>
                    @if (session('success'))
                        <div class="w-full max-w-2xl">
                            <div class="alert alert-success my-4 w-full text-white">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="w-full max-w-2xl">
                            <div class="alert alert-error my-4 w-full text-white">
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif
                    <div class="flex gap-2 mt-8">
                        <a href="{{ route('events') }}"
                            class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2">
                            {{ __('website/visit-event.go_to_home') }}
                        </a>
                        <a href="{{ route('event_details', ['slug' => $event->slug]) }}"
                            class="btn font-semibold btn-sm rounded-md btn-primary">
                            {{ __('website/visit-event.back_to_event') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
