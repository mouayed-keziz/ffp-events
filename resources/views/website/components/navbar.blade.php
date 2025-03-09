<div class="drawer drawer-end">
    <input id="navbar-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content">
        <div class="bg-white shadow-sm">
            <div class="w-full max-w-7xl mx-auto">
                <div class="navbar">
                    <div class="navbar-start h-16">
                        <a href="{{ route('events') }}">
                            @include('website.components.brand.logo')
                        </a>
                    </div>

                    <div class="navbar-center hidden md:flex flex-none gap-8">
                        <a href="{{ route('events') }}"
                            class="group flex items-center gap-2 font-bold {{ request()->routeIs('events') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            <span class="{{ !request()->routeIs('events') ? 'group-hover:hidden' : 'hidden' }}">
                                @include('website.svg.events')
                            </span>
                            <span
                                class="{{ request()->routeIs('events') || !request()->routeIs('events') ? 'group-hover:block' : '' }} {{ request()->routeIs('events') ? 'block' : 'hidden' }}">
                                @include('website.svg.events_active')
                            </span>
                            <span>{{ __('website/navbar.events') }}</span>
                        </a>

                        <a href="{{ route('articles') }}"
                            class="group flex items-center gap-2 font-bold {{ request()->routeIs('articles') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            <span class="{{ !request()->routeIs('articles') ? 'group-hover:hidden' : 'hidden' }}">
                                @include('website.svg.articles')
                            </span>
                            <span
                                class="{{ request()->routeIs('articles') || !request()->routeIs('articles') ? 'group-hover:block' : '' }} {{ request()->routeIs('articles') ? 'block' : 'hidden' }}">
                                @include('website.svg.articles_active')
                            </span>
                            <span>{{ __('website/navbar.articles') }}</span>
                        </a>
                    </div>

                    <div class="navbar-end space-x-4">
                        <div class="flex items-center gap-2 md:gap-4">
                            @include('website.components.navbar.local-dropdown')
                            <div class="md:hidden">
                                @if (checkUser())
                                    @livewire('website.user-menu')
                                @endif
                            </div>
                            <div class="hidden md:flex items-center gap-2 md:gap-4">
                                @if (checkUser())
                                    @livewire('website.user-menu')
                                @else
                                    <a href="{{ route('login') }}"
                                        class="btn btn-outline text-[1rem] border-base-200 border-2">
                                        {{ __('website/navbar.login') }}
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-primary text-[1rem]">
                                        {{ __('website/navbar.register') }}
                                    </a>
                                @endif
                            </div>
                            <label for="navbar-drawer" class="btn btn-square btn-ghost md:hidden">
                                @include('website.svg.burger')
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="drawer-side z-50">
        <label for="navbar-drawer" class="drawer-overlay"></label>
        <div class="menu p-4 w-80 min-h-full bg-white">
            <div class="flex flex-col gap-4">
                <a href="{{ route('events') }}"
                    class="group flex items-center gap-2 font-bold {{ request()->routeIs('events') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    <span class="{{ !request()->routeIs('events') ? 'group-hover:hidden' : 'hidden' }}">
                        @include('website.svg.events')
                    </span>
                    <span
                        class="{{ request()->routeIs('events') || !request()->routeIs('events') ? 'group-hover:block' : '' }} {{ request()->routeIs('events') ? 'block' : 'hidden' }}">
                        @include('website.svg.events_active')
                    </span>
                    <span class="text-lg">{{ __('website/navbar.events') }}</span>
                </a>
                <a href="{{ route('articles') }}"
                    class="group flex items-center gap-2 font-bold {{ request()->routeIs('articles') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    <span class="{{ !request()->routeIs('articles') ? 'group-hover:hidden' : 'hidden' }}">
                        @include('website.svg.articles')
                    </span>
                    <span
                        class="{{ request()->routeIs('articles') || !request()->routeIs('articles') ? 'group-hover:block' : '' }} {{ request()->routeIs('articles') ? 'block' : 'hidden' }}">
                        @include('website.svg.articles_active')
                    </span>
                    <span class="text-lg">{{ __('website/navbar.articles') }}</span>
                </a>
                <div class="border-t my-4"></div>
                @if (checkUser())
                    {{-- Optionally include the user menu in drawer if needed --}}
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline text-[1rem] border-base-200 border-2">
                        {{ __('website/navbar.login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-[1rem]">
                        {{ __('website/navbar.register') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
