<div class="dropdown dropdown-end">
    <div tabindex="0" role="button" class="btn btn-square btn-link btn-sm">
        <div class="w-8">
            @if (session('locale') === 'ar')
                @include('website.svg.flags.algeria')
            @elseif(session('locale') === 'en')
                @include('website.svg.flags.usa')
            @else
                @include('website.svg.flags.france')
            @endif
        </div>
    </div>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-white rounded-box w-52">
        <li>
            <a href="{{ route('language.switch', 'fr') }}"
                class="flex items-center gap-2 hover:bg-gray-300/40 {{ session('locale') === 'fr' ? 'bg-gray-300/40' : '' }}">
                <div class="w-8">@include('website.svg.flags.france')</div>
                <span class="font-semibold">Français</span>
            </a>
        </li>
        <li>
            <a href="{{ route('language.switch', 'en') }}"
                class="flex items-center gap-2 hover:bg-gray-300/40 {{ session('locale') === 'en' ? 'bg-gray-300/40' : '' }}">
                <div class="w-8">@include('website.svg.flags.usa')</div>
                <span class="font-semibold">English</span>
            </a>
        </li>
        <li>
            <a href="{{ route('language.switch', 'ar') }}"
                class="flex items-center gap-2 hover:bg-gray-300/40 {{ session('locale') === 'ar' ? 'bg-gray-300/40' : '' }}">
                <div class="w-8">@include('website.svg.flags.algeria')</div>
                <span class="font-semibold">العربية</span>
            </a>
        </li>
    </ul>
</div>
