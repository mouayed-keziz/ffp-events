{{-- resources/views/website/components/footer.blade.php --}}
@php
    use App\Settings\CompanyInformationsSettings;
    $settings = app(CompanyInformationsSettings::class);
@endphp

@if (!empty($hasContactCard))
    <div class="relative -mb-24 z-10">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto bg-white rounded-xl shadow-2xl p-6 text-center">
                <h2 class="text-xl font-bold mb-2">{{ __('website/footer.contact_card.title') }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ __('website/footer.contact_card.description') }}</p>
                <a href="{{ route('redirect_to_ffp_events_contact') }}" target="_blank"
                    class="btn btn-neutral normal-case text-sm">
                    {{ __('website/footer.contact_card.button') }}
                </a>
            </div>
        </div>
    </div>
@endif

<footer class="bg-[#1C1C1C] text-white py-8 md:py-12">
    <div class="w-full max-w-7xl mx-auto px-4">
        @if (!empty($hasContactCard))
            <div class="mt-24"></div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="h-16 mb-8 md:mb-0">
                    @include('website.components.brand.logo-dark')
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold">{{ __('website/footer.contact_info.phone') }}</span>
                        <a href="tel:{{ $settings->phone }}"
                            class="text-sm text-neutral-400 hover:text-primary transition-colors">
                            {{ $settings->phone }}
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold">{{ __('website/footer.contact_info.email') }}</span>
                        <a href="mailto:{{ $settings->email }}"
                            class="text-sm text-neutral-400 hover:text-primary transition-colors">
                            {{ $settings->email }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="md:col-start-3">
                <h3 class="text-neutral-400 text-[0.9rem] mb-4">{{ __('website/footer.quick_links.title') }}</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('events') }}" class="text-sm hover:text-primary transition-colors">
                            {{ __('website/footer.quick_links.events') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('articles') }}" class="text-sm hover:text-primary transition-colors">
                            {{ __('website/footer.quick_links.articles') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="text-sm hover:text-primary transition-colors">
                            {{ __('website/footer.quick_links.terms') }}
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-neutral-400 text-[0.9rem] mb-4">{{ __('website/footer.company.title') }}</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('redirect_to_ffp_events') }}" target="_blank"
                            class="text-sm hover:text-primary transition-colors inline-flex items-center gap-2 group">
                            {{ $settings->name }}
                            <span class="block group-hover:hidden">
                                @include('website.svg.footer.goto')
                            </span>
                            <span class="hidden group-hover:block">
                                @include('website.svg.footer.goto_active')
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('redirect_to_ffp_events_contact') }}" target="_blank"
                            class="text-sm hover:text-primary transition-colors inline-flex items-center gap-2 group">
                            {{ __('website/footer.company.contact_us') }}
                            <span class="block group-hover:hidden">
                                @include('website.svg.footer.goto')
                            </span>
                            <span class="hidden group-hover:block">
                                @include('website.svg.footer.goto_active')
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-zinc-700 mt-6"></div>

        {{-- Footer Bottom --}}
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-xs text-gray-400">
                {!! __('website/footer.copyright', ['year' => date('Y'), 'company' => $settings->name]) !!}
            </div>

            <div class="flex items-center gap-4">
                @if ($settings->facebookLink)
                    <a href="{{ $settings->facebookLink }}" target="_blank" rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors">
                        @include('website.svg.footer.facebook')
                    </a>
                @endif

                @if ($settings->instagramLink)
                    <a href="{{ $settings->instagramLink }}" target="_blank" rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors">
                        @include('website.svg.footer.instagram')
                    </a>
                @endif

                @if ($settings->linkedinLink)
                    <a href="{{ $settings->linkedinLink }}" target="_blank" rel="noopener noreferrer"
                        class="text-gray-400 hover:text-white transition-colors">
                        @include('website.svg.footer.linkedin')
                    </a>
                @endif
            </div>
        </div>
    </div>
</footer>
