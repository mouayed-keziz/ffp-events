<div class="dropdown dropdown-end shrink-0">
    <label tabindex="0" class="p-2 flex items-center gap-2 cursor-pointer">
        <span class="font-semibold">{{ __('website/articles.sort.label') }}
            {{ __('website/articles.sort.' . $sortBy) }}</span>
        <x-heroicon-o-chevron-down class="h-4 w-4" />
    </label>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-white rounded-box w-52">
        <li><a wire:click="$set('sortBy', 'newest')"
                class="{{ $sortBy === 'newest' ? 'active font-semibold' : 'font-semibold' }}">{{ __('website/articles.sort.newest') }}</a>
        </li>
        <li><a wire:click="$set('sortBy', 'oldest')"
                class="{{ $sortBy === 'oldest' ? 'active font-semibold' : 'font-semibold' }}">{{ __('website/articles.sort.oldest') }}</a>
        </li>
        <li><a wire:click="$set('sortBy', 'most_viewed')"
                class="{{ $sortBy === 'most_viewed' ? 'active font-semibold' : 'font-semibold' }}">{{ __('website/articles.sort.most_viewed') }}</a>
        </li>
        <li><a wire:click="$set('sortBy', 'most_shared')"
                class="{{ $sortBy === 'most_shared' ? 'active font-semibold' : 'font-semibold' }}">{{ __('website/articles.sort.most_shared') }}</a>
        </li>
    </ul>
</div>
