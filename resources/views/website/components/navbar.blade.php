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
                        <a href="{{ route('events') }}" class="flex items-center gap-2 font-bold {{ request()->routeIs('events') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            @if(request()->routeIs('events'))
                                @include('website.svg.events_active')
                            @else
                                @include('website.svg.events')
                            @endif
                            <span>{{ __('website/navbar.events') }}</span>
                        </a>

                        <a href="{{ route('articles') }}" class="flex items-center gap-2 font-bold {{ request()->routeIs('articles') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                            @if(request()->routeIs('articles'))
                                @include('website.svg.articles_active')
                            @else
                                @include('website.svg.articles')
                            @endif
                            <span>{{ __('website/navbar.articles') }}</span>
                        </a>
                    </div>

                    <div class="navbar-end space-x-4">
                        <div class="flex items-center gap-4">
                            @include('website.components.local-dropdown')
                            <div class="hidden md:flex items-center gap-4">
                                <a href="{{ route('login') }}" class="btn btn-outline text-[1rem] border-base-200 border-2">
                                    {{ __('website/navbar.login') }}
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-primary text-[1rem]">
                                    {{ __('website/navbar.register') }}
                                </a>
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
                <a href="{{ route('events') }}" class="flex items-center gap-2 font-bold {{ request()->routeIs('events') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    @if(request()->routeIs('events'))
                        @include('website.svg.events_active')
                    @else
                        @include('website.svg.events')
                    @endif
                    <span class="text-lg">{{ __('website/navbar.events') }}</span>
                </a>
                <a href="{{ route('articles') }}" class="flex items-center gap-2 font-bold {{ request()->routeIs('articles') ? 'text-primary' : 'text-gray-700 hover:text-primary' }}">
                    @if(request()->routeIs('articles'))
                        @include('website.svg.articles_active')
                    @else
                        @include('website.svg.articles')
                    @endif
                    <span class="text-lg">{{ __('website/navbar.articles') }}</span>
                </a>
                <div class="border-t my-4"></div>
                <a href="{{ route('login') }}" class="btn btn-outline text-[1rem] border-base-200 border-2">
                    {{ __('website/navbar.login') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary text-[1rem]">
                    {{ __('website/navbar.register') }}
                </a>
            </div>
        </div>
    </div>
</div>
