<!-- resources/views/components/navbar.blade.php -->
<div class="drawer">
    <input id="navbar-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content">
        <!-- Navbar -->
        <div class="navbar bg-base-100 shadow-md backdrop-blur bg-opacity-90 sticky top-0 z-50">
            <div class="navbar-start">
                <label for="navbar-drawer" class="btn btn-ghost drawer-button lg:hidden">
                    <x-heroicon-o-bars-3 class="w-5 h-5" />
                </label>
                <a href="/" class="btn btn-ghost normal-case text-xl hover:bg-transparent">
                    <img src="{{ asset('favicon.svg') }}" alt="Logo"
                        class="h-8 w-8 ltr:mr-2 rtl:ml-2 transition-transform hover:scale-110">
                    <span class="font-bold">{{ config('app.name') }}</span>
                </a>
            </div>

            <div class="navbar-end">
                <div class="hidden lg:flex items-center ltr:mr-4 rtl:ml-4">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('blog.index') }}"
                            class="link link-hover transition-colors {{ request()->routeIs('blog.index') ? 'link-primary underline' : '' }}">
                            {{ __('website/navbar.blog') }}
                        </a>
                        <a href="{{ route('blog.categories') }}"
                            class="link link-hover transition-colors {{ request()->routeIs('blog.categories') ? 'link-primary underline' : '' }}">
                            {{ __('website/navbar.categories') }}
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="#" class="btn btn-sm hidden lg:flex">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-user class="w-4 h-4" />
                            <span>{{ __('website/navbar.login') }}</span>
                        </div>
                    </a>
                    @include('website.components.local-dropdown')
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile drawer -->
    <div class="drawer-side z-50">
        <label for="navbar-drawer" class="drawer-overlay"></label>
        <div class="p-4 w-80 h-full bg-base-100 flex flex-col">
            <!-- Drawer Header -->
            <div class="border-b border-base-200 pb-4">
                <a href="/" class="text-xl font-bold flex items-center">
                    <img src="{{ asset('favicon.svg') }}" alt="Logo" class="h-8 w-8 ltr:mr-2 rtl:ml-2">
                    {{ config('app.name') }}
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-2 mt-6">
                <a href="{{ route('blog.index') }}"
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200 transition-colors {{ request()->routeIs('blog.index') ? 'bg-primary/10 text-primary' : '' }}">
                    <x-heroicon-o-newspaper class="w-5 h-5" />
                    <span>{{ __('website/navbar.blog') }}</span>
                </a>
                <a href="{{ route('blog.categories') }}"
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200 transition-colors {{ request()->routeIs('blog.categories') ? 'bg-primary/10 text-primary' : '' }}">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5" />
                    <span>{{ __('website/navbar.categories') }}</span>
                </a>
            </nav>

            <!-- Bottom Section -->
            <div class="mt-auto border-t border-base-200 pt-4">
                <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-base-200 transition-colors">
                    <x-heroicon-o-user class="w-5 h-5" />
                    <span>{{ __('website/navbar.login') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
