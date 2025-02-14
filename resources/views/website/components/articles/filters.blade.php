<div class="mx-4 md:mx-8 relative min-w-0 flex-1" x-data="{
    scroll: 0,
    maxScroll: 0,
    canScrollLeft: false,
    canScrollRight: false,
    isRTL: document.documentElement.getAttribute('dir') === 'rtl', // detect RTL
    updateScroll() {
        const container = this.$refs.scrollContainer;
        this.maxScroll = Math.max(0, container.scrollWidth - container.clientWidth);
        let s = this.isRTL ? Math.abs(container.scrollLeft) : container.scrollLeft;
        this.scroll = s;
        if (this.isRTL) {
            this.canScrollRight = s > 0;
            this.canScrollLeft = s < (this.maxScroll - 1);
            container.style.maskImage = `linear-gradient(to left, ${this.canScrollRight ? 'transparent' : 'black'}, black 15%, black 85%, ${this.canScrollLeft ? 'transparent' : 'black'} 100%)`;
        } else {
            this.canScrollLeft = s > 0;
            this.canScrollRight = s < (this.maxScroll - 1);
            container.style.maskImage = `linear-gradient(to right, ${this.canScrollLeft ? 'transparent' : 'black'}, black 15%, black 85%, ${this.canScrollRight ? 'transparent' : 'black'} 100%)`;
        }
        container.style.webkitMaskImage = container.style.maskImage;
    },
    scrollLeft() {
        // In RTL, scroll left button scrolls right
        this.$refs.scrollContainer.scrollBy({ left: this.isRTL ? 200 : -200, behavior: 'smooth' });
    },
    scrollRight() {
        // In RTL, scroll right button scrolls left
        this.$refs.scrollContainer.scrollBy({ left: this.isRTL ? -200 : 200, behavior: 'smooth' });
    }
}" x-init="updateScroll(); window.addEventListener('resize', updateScroll)">
    <!-- Left scroll button -->
    <button
        @click="scrollLeft()"
        x-show="isRTL ? canScrollRight : canScrollLeft"
        :class="isRTL ? 'absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white shadow-lg border border-base-200 flex items-center justify-center transition-opacity duration-200' : 'absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white shadow-lg border border-base-200 flex items-center justify-center transition-opacity duration-200'">
        <!-- Conditionally swap chevron icon -->
        <template x-if="isRTL">
            <x-heroicon-o-chevron-right class="h-4 w-4 sm:h-6 sm:w-6" />
        </template>
        <template x-if="!isRTL">
            <x-heroicon-o-chevron-left class="h-4 w-4 sm:h-6 sm:w-6" />
        </template>
    </button>

    <!-- Buttons container with custom scrollbar -->
    <div x-ref="scrollContainer" @scroll="updateScroll()"
        class="flex gap-2 overflow-x-auto scrollbar-hide relative min-w-0 w-full">
        <button class="btn rounded-badge btn-neutral border-zinc-200 whitespace-nowrap">Tous</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Actualités</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
        <button class="btn rounded-badge bg-white border-zinc-200 whitespace-nowrap">Événements</button>
    </div>

    <!-- Right scroll button -->
    <button
        @click="scrollRight()"
        x-show="isRTL ? canScrollLeft : canScrollRight"
        :class="isRTL ? 'absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white shadow-lg border border-base-200 flex items-center justify-center transition-opacity duration-200' : 'absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white shadow-lg border border-base-200 flex items-center justify-center transition-opacity duration-200'">
        <!-- Conditionally swap chevron icon -->
        <template x-if="isRTL">
            <x-heroicon-o-chevron-left class="h-4 w-4 sm:h-6 sm:w-6" />
        </template>
        <template x-if="!isRTL">
            <x-heroicon-o-chevron-right class="h-4 w-4 sm:h-6 sm:w-6" />
        </template>
    </button>
</div>
