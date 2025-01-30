<!DOCTYPE html>
<html dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-theme="ffp-theme-light" lang="{{ App::getLocale() }}">

<head>
    <meta charset="{{ __('website/layout.meta.charset') }}">
    <meta name="viewport" content="{{ __('website/layout.meta.viewport') }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">
    @include('website.components.navbar')

    <main class="container mx-auto px-4 py-8 flex-grow">
        @yield('content')
    </main>

</body>

</html>
