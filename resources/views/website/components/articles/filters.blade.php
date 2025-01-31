<div class="mx-4 md:mx-8 relative min-w-0 flex-1" x-data="{
    scroll: 0,
    maxScroll: 0,
    canScrollLeft: false,
    canScrollRight: false,
    updateScroll() {
        const container = this.$refs.scrollContainer;
        this.maxScroll = Math.max(0, container.scrollWidth - container.clientWidth);
        this.scroll = container.scrollLeft;
        this.canScrollLeft = this.scroll > 0;
        this.canScrollRight = this.scroll < (this.maxScroll - 1); // Added tolerance

        // Update gradient based on scroll position
        if (this.maxScroll > 0) {
            container.style.maskImage = `linear-gradient(to right, 
                ${this.canScrollLeft ? 'transparent' : 'black'}, 
                black 15%, 
                black 85%, 
                ${this.canScrollRight ? 'transparent' : 'black'} 100%)`;
            container.style.webkitMaskImage = container.style.maskImage;
        } else {
            container.style.maskImage = 'none';
            container.style.webkitMaskImage = 'none';
        }
    },
    scrollLeft() {
        this.$refs.scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
    },
    scrollRight() {
        this.$refs.scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
    }
}" x-init="updateScroll();
window.addEventListener('resize', updateScroll)">

    <!-- Left scroll button -->
    <button
        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white shadow-lg border border-base-200 flex items-center justify-center transition-opacity duration-200"
        :class="{ 'opacity-0 pointer-events-none': !canScrollLeft, 'opacity-100': canScrollLeft }" @click="scrollLeft()">
        <x-heroicon-o-chevron-left class="h-4 w-4 sm:h-6 sm:w-6" />
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
        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 z-10 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white shadow-lg border border-base-200 flex items-center justify-center transition-opacity duration-200"
        :class="{ 'opacity-0 pointer-events-none': !canScrollRight, 'opacity-100': canScrollRight }"
        @click="scrollRight()">
        <x-heroicon-o-chevron-right class="h-4 w-4 sm:h-6 sm:w-6" />
    </button>
</div>
