@extends('website.layouts.app')

@section('content')
    <main class="w-full max-w-7xl mx-auto px-4 py-8 flex-grow">
        <h1 class="text-3xl font-bold mb-8 mt-12">
            {{ __('Terms & Conditions') }}
        </h1>
        @php
            $applicationTerms = app(\App\Settings\CompanyInformationsSettings::class)->applicationTerms;
        @endphp
    <div class='prose'>
        {!! $applicationTerms !!}
    </div>

    </main>
@endsection
