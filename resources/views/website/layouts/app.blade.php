<!DOCTYPE html>
<html dir="ltr" data-theme="ffp-stheme" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-base-200/20 flex flex-col">
    @include('website.components.navbar')

    <main class="w-full max-w-7xl mx-auto px-4 py-8 flex-grow">
        @yield('content')
    </main>

    @include('website.components.footer')
</body>

</html>
