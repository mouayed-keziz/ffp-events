@extends('website.layouts.auth')

@section('content')
    <div class="flex justify-center mt-24">
        <div class="card w-full max-w-sm shadow-lg bg-white rounded-lg pt-4 pb-10 px-4">
            <div class="w-1/1 mb-6 mx-auto">
                @include('website.svg.email-sent')
            </div>
            <h2 class="text-xl font-bold text-center mb-2">
                {{ __('website/email-sent.title') }}
            </h2>
            <p class="text-xs md:text-sm text-center text-neutral-500">
                {{ __('website/email-sent.message_before_email') }}
                <strong class="font-bold">{{ $email }}</strong>
                {{ __('website/email-sent.message_after_email') }}
            </p>
        </div>
    </div>
@endsection
