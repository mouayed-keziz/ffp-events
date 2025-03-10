<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div x-data="{ open: false }" class="relative">
    <!-- Main share button -->
    <button @click="open = !open" class="btn btn-primary btn-circle btn-md md:btn-md shrink-0 z-10 relative">
        @include('website.svg.share.share')
    </button>

    <!-- Social media buttons container -->
    <div class="absolute bottom-0 right-full flex items-center gap-2">
        <!-- Facebook -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12 scale-0"
            x-transition:enter-end="opacity-100 translate-x-0 scale-90"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-90"
            x-transition:leave-end="opacity-0 translate-x-12 scale-0"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-600 shadow-md transform scale-90 mr-1"
            style="transition-delay: 200ms;">
            @include('website.svg.share.facebook')
        </button>

        <!-- Twitter/X -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12 scale-0"
            x-transition:enter-end="opacity-100 translate-x-0 scale-90"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-90"
            x-transition:leave-end="opacity-0 translate-x-12 scale-0"
            class="btn btn-circle btn-md md:btn-md bg-white text-gray-800 shadow-md transform scale-90 mr-1"
            style="transition-delay: 150ms;">
            @include('website.svg.share.instagram')
        </button>

        <!-- LinkedIn -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12 scale-0"
            x-transition:enter-end="opacity-100 translate-x-0 scale-90"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-90"
            x-transition:leave-end="opacity-0 translate-x-12 scale-0"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-700 shadow-md transform scale-90 mr-1"
            style="transition-delay: 100ms;">
            @include('website.svg.share.linkedin')
        </button>
    </div>
</div>
