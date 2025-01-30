<div class="drawer drawer-end">
    <input id="navbar-drawer" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content">
        <div class="bg-white shadow-sm">
            <div class="container mx-auto">
                <div class="navbar">
                    <div class="navbar-start h-16">
                        @include('website.components.brand.logo')
                    </div>

                    <div class="navbar-center hidden md:flex flex-none gap-8">
                        <a href="#" class="flex items-center gap-2 text-primary font-bold">
                            @include('website.svg.events')
                            <span>Nos evenements</span>
                        </a>

                        <a href="#"
                            class="flex items-center gap-2 text-gray-700 hover:text-primary transition-colors font-bold">
                            @include('website.svg.articles')
                            <span>Nos articles</span>
                        </a>
                    </div>

                    <div class="navbar-end space-x-4">
                        <div class="flex items-center gap-4">
                            <button class="btn btn-square btn-ghost">
                                <div class="w-8">
                                    @include('website.svg.flags.france')
                                </div>
                            </button>
                            <div class="hidden md:flex items-center gap-4">
                                <a href="#" class="btn btn-outline text-[1rem] border-base-200 border-2">
                                    Se connecter
                                </a>
                                <a href="#" class="btn btn-primary text-[1rem]">
                                    S'inscrire
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

    <div class="drawer-side">
        <label for="navbar-drawer" class="drawer-overlay"></label>
        <div class="menu p-4 w-80 min-h-full bg-white">
            <div class="flex flex-col gap-4">
                <a href="#" class="flex items-center gap-2 text-primary font-bold">
                    @include('website.svg.events')
                    <span class="text-lg">Nos evenements</span>
                </a>
                <a href="#"
                    class="flex items-center gap-2 text-gray-700 hover:text-primary transition-colors font-bold">
                    @include('website.svg.articles')
                    <span class="text-lg">Nos articles</span>
                </a>
                <div class="border-t my-4"></div>
                <a href="#" class="btn btn-outline text-[1rem] border-base-200 border-2">
                    Se connecter
                </a>
                <a href="#" class="btn btn-primary text-[1rem]">
                    S'inscrire
                </a>
            </div>
        </div>
    </div>
</div>
