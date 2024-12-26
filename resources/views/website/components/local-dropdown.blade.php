<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-sm m-1">
        {{-- <span class="bg-primary/20 p-1 rounded text-sm uppercase">{{ App::getLocale() }}</span> --}}
        <x-heroicon-o-language class="w-4 h-4 ltr:ml-2 rtl:mr-2" />
        {{ __('website/navbar.languages.' . App::getLocale()) }}
    </div>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box">
        @foreach (['en', 'ar', 'fr'] as $locale)
            @if (App::getLocale() != $locale)
                <li>
                    <a href="{{ route('language.switch', $locale) }}" class="flex items-center gap-2">
                        <span
                            class="bg-primary/10 text-primary font-bold p-1 rounded text-sm uppercase">{{ $locale }}</span>
                        {{ __('website/navbar.languages.' . $locale) }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
