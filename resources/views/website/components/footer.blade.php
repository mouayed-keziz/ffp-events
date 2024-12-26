@php
    use App\Settings\CompanyInformationsSettings;
    $settings = app(CompanyInformationsSettings::class);
@endphp

<footer class="bg-base-100 border-t border-base-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">{{ $settings->name }}</h3>
                <div class="space-y-2 text-base-content/80">
                    <p class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $settings->address }},
                        {{ $settings->city }},
                        {{ $settings->state }} {{ $settings->zip }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ $settings->email }}
                    </p>
                    <p class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $settings->phone }}
                    </p>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">{{ __('website/footer.quick_links') }}</h3>
                <ul class="space-y-2 text-base-content/80">
                    <li><a href="/" class="hover:text-primary">{{ __('website/footer.home') }}</a></li>
                    <li><a href="/" class="hover:text-primary">{{ __('website/footer.events') }}</a></li>
                    <li><a href="/" class="hover:text-primary">{{ __('website/footer.about_us') }}</a></li>
                    <li><a href="/" class="hover:text-primary">{{ __('website/footer.contact') }}</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">{{ __('website/footer.newsletter') }}</h3>
                <p class="text-base-content/80">{{ __('website/footer.newsletter_desc') }}</p>
                <form class="flex gap-2">
                    <input type="email" placeholder="{{ __('website/footer.email_placeholder') }}"
                        class="input input-bordered flex-1" />
                    <button class="btn btn-primary">{{ __('website/footer.subscribe') }}</button>
                </form>
            </div>
        </div>

        <div class="border-t border-base-300 mt-12 pt-8 text-center text-base-content/70">
            <p>© {{ date('Y') }} {{ $settings->name }}. {{ __('website/footer.all_rights_reserved') }}</p>
        </div>
    </div>
</footer>
