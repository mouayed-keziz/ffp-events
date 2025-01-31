<div class="dropdown dropdown-end shrink-0">
    <label tabindex="0" class="p-2 flex items-center gap-2 cursor-pointer">
        <span class="font-semibold">Afficher par : {{ $sortBy }}</span>
        <x-heroicon-o-chevron-down class="h-4 w-4" />
    </label>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
        <li><a wire:click="$set('sortBy', 'Date')">Date</a></li>
        <li><a wire:click="$set('sortBy', 'Popularité')">Popularité</a></li>
    </ul>
</div>
