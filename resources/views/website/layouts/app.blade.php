<!DOCTYPE html>
<html dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="ffp-theme-light" lang="{{ App::getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap');
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="min-h-screen flex flex-col">
    @yield('before-navbar')
    @include('website.components.navbar')
    @yield('after-navbar')

    @yield('content')

    @if (request()->routeIs('events'))
        @include('website.components.footer', ['hasContactCard' => true])
    @else
        @include('website.components.footer')
    @endif
</body>

</html>
