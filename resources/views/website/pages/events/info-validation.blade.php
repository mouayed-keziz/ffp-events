@extends('website.layouts.app')
@php
    $actions = new \App\Forms\ExhibitorFormActions();
    $formData = $actions->initFormData($event);
    $postForms = $event->exhibitorPostPaymentForms->toArray();
    $currentStep = count($formData); // Info validation is after all form steps
@endphp
@section('content')
    @include('website.components.exhibit-event.banner', [
        'event' => $event,
        'title' => 'Exposer dans ' . $event->title,
    ])
    <main class="w-full max-w-5xl mx-auto px-4 py-8">
        <div class="-mt-40 relative z-10">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="container mx-auto pt-8 md:px-4">
                    @include('website.components.forms.multi-step-form', [
                        'steps' => $formData,
                        'postForms' => $postForms,
                        'currentStep' => $currentStep,
                        'errors' => $errors ?? null,
                        'formSubmitted' => false,
                        'successMessage' => '',
                    ])
                </div>
                <div class="flex flex-col items-center justify-center py-4">
                    <div class="h-64">
                        <img src="{{ asset('rocket.png') }}" alt="Success" class="w-full h-full">
                    </div>

                    <h2 class="text-xl font-bold mt-6 text-center">
                        {{ __('website/visit-event.registration_success', ['event' => $event->title]) }}
                    </h2>

                    <p class="text-gray-400 text-sm mt-4 text-center max-w-3xl">
                        {{ __('website/visit-event.badge_instructions') }}
                    </p>

                    <div class="w-full flex justify-start items-center gap-2 mt-8">
                        <a href="{{ route('view_exhibitor_answers', ['id' => $event->id]) }}"
                            class="btn font-semibold btn-sm rounded-md btn-outline border-base-200 border-2">
                            revoir mes reponses
                        </a>
                        <a href="{{ route('event_details', ['id' => $event->id]) }}"
                            class="btn font-semibold btn-sm rounded-md btn-primary">
                            retourner vers l'evenement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
