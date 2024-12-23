<!DOCTYPE html>
<html data-theme="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-100">
    <div class="navbar bg-base-100 shadow">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
                </label>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('blog.categories') }}">Categories</a></li>
                </ul>
            </div>
            <a href="/" class="btn btn-ghost normal-case text-xl">{{ config('app.name') }}</a>
        </div>
        <div class="navbar-end hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li><a href="{{ route('blog.categories') }}">Categories</a></li>
            </ul>
        </div>
    </div>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="footer footer-center p-10 bg-base-200 text-base-content rounded">
        <div>
            <p>Copyright © {{ date('Y') }} - All rights reserved by {{ config('app.name') }}</p>
        </div>
    </footer>
</body>
</html>
