<div class="dropdown dropdown-end shrink-0">
    <label tabindex="0" class="p-2 flex items-center gap-2 cursor-pointer">
        <span class="font-semibold">Afficher par : {{ $sortBy }}</span>
        <x-heroicon-o-chevron-down class="h-4 w-4" />
    </label>
    <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-white rounded-box w-52">
        <li><a wire:click="$set('sortBy', 'Les plus récents')"
                class="{{ $sortBy === 'Les plus récents' ? 'active font-semibold' : 'font-semibold' }}">Les plus
                récents</a></li>
        <li><a wire:click="$set('sortBy', 'Les plus anciens')"
                class="{{ $sortBy === 'Les plus anciens' ? 'active font-semibold' : 'font-semibold' }}">Les plus
                anciens</a></li>
        <li><a wire:click="$set('sortBy', 'Les plus vus')"
                class="{{ $sortBy === 'Les plus vus' ? 'active font-semibold' : 'font-semibold' }}">Les plus vus</a>
        </li>
        <li><a wire:click="$set('sortBy', 'Les plus partagés')"
                class="{{ $sortBy === 'Les plus partagés' ? 'active font-semibold' : 'font-semibold' }}">Les plus
                partagés</a></li>
    </ul>
</div>
