@extends('website.layouts.app')

@section('title', config('app.name') . ' - ' . $event->title)

@section('meta')
    <meta name="description" content="{{ $event->description }}">
@endsection

@section('head')
    <!-- Add any page-specific scripts or styles here -->
@endsection

@section('content')
    @include('website.components.event.banner', ['event' => $event])
    <div class="w-full max-w-5xl mx-auto px-4 py-8">
        @include('website.components.event.details', ['event' => $event])
    </div>
    <div class="w-full max-w-5xl mx-auto px-4 py-8">
        @include('website.components.home.events', ['events' => $relatedEvents])
    </div>
@endsection
