<!DOCTYPE html>
<html dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="ffp-theme-light" lang="{{ App::getLocale() }}">

<head>
    <meta charset="{{ __('website/layout.meta.charset') }}">
    <meta name="viewport" content="{{ __('website/layout.meta.viewport') }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-glow">
    @include('website.components.navbar')

    @yield('content')
</body>

</html>
