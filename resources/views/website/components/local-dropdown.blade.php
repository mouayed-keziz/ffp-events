<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-sm m-1">
        <x-heroicon-o-language class="w-4 h-4 ltr:mr-2 rtl:ml-2" />
        {{ __('website/navbar.languages.' . App::getLocale()) }}
    </div>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
        @foreach (['en', 'ar', 'fr'] as $locale)
            @if (App::getLocale() != $locale)
                <li>
                    <a href="{{ route('language.switch', $locale) }}" class="flex items-center gap-2">
                        {{ __('website/navbar.languages.' . $locale) }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
