<?php

use Livewire\Volt\Component;

new class extends Component {
    public function isRtl()
    {
        return in_array(app()->getLocale(), ['ar', 'arabic']);
    }
}; ?>

<div x-data="{ open: false }" class="relative">
    <!-- Main share button -->
    <button @click="open = !open"
        class="btn btn-primary btn-circle btn-md md:btn-md shrink-0 z-20 relative hover:scale-105 transition-transform duration-200">
        @include('website.svg.share.share')
    </button>

    <!-- Social media buttons container -->
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="{{ $this->isRtl() ? 'md:left-full md:right-auto' : 'md:right-full md:left-auto' }} 
            absolute bottom-full md:bottom-0 flex flex-col md:flex-row items-center gap-1 md:gap-0.5 p-1 z-10">

        <!-- Facebook -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-600 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-blue-50 transition-all"
            style="transition-delay: 120ms;"
            @click="navigator.share ? navigator.share({url: window.location.href, title: document.title}) : window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank')">
            @include('website.svg.share.facebook')
        </button>

        <!-- Instagram -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-gray-800 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-gray-50 transition-all"
            style="transition-delay: 80ms;" @click="window.open('https://www.instagram.com/', '_blank')">
            @include('website.svg.share.instagram')
        </button>

        <!-- LinkedIn -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-700 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-blue-50 transition-all"
            style="transition-delay: 40ms;"
            @click="window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(window.location.href), '_blank')">
            @include('website.svg.share.linkedin')
        </button>
    </div>
</div>
