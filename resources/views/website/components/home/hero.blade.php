@php
    $placeholderImages = [
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070&auto=format&fit=crop',
        'https://www.cvent.com/sites/default/files/image/2019-08/blog-topic-header-connect.jpg',
        'https://vancouver.websummit.com/wp-media/2024/06/Centre-Stage-during-the-opening-night-of-Web-Summit-Rio-2023-at-Riocentro-in-Rio-de-Janeiro-Brazil.jpg',
        'https://arabfounders.net/wp-content/uploads/2024/12/Web-Summit-Qatar-2025.webp',
    ];
@endphp

<div x-data="{
    activeSlide: 0,
    slides: {{ count($placeholderImages) }},
    startX: 0,
    currentX: 0,
    isDragging: false,
    threshold: 50,
    isRTL: false, // added for RTL detection

    init() {
        this.isRTL = document.documentElement.getAttribute('dir') === 'rtl'; // detect text direction
        this.autoPlay();
    },

    autoPlay() {
        setInterval(() => {
            if (!this.isDragging) {
                this.next();
            }
        }, 6180);
    },

    next() {
        this.activeSlide = (this.activeSlide + 1) % this.slides;
    },

    prev() {
        this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides;
    },

    startDrag(e) {
        this.isDragging = true;
        this.startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
    },

    drag(e) {
        if (!this.isDragging) return;
        e.preventDefault();
        this.currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
    },
    endDrag() {
        if (!this.isDragging) return;
        const diff = this.startX - this.currentX;
        if (Math.abs(diff) > this.threshold) {
            if (this.isRTL) {
                diff > 0 ? this.prev() : this.next();
            } else {
                diff > 0 ? this.next() : this.prev();
            }
        }
        this.isDragging = false;
    }
}"
    class="relative overflow-hidden rounded-btn mb-12 aspect-[7/4] md:aspect-video lg:aspect-[3/1] select-none"
    @touchstart="startDrag" @mousedown="startDrag" @touchmove="drag" @mousemove="drag" @touchend="endDrag" @mouseup="endDrag"
    @mouseleave="endDrag" @dragstart.prevent>

    <!-- Slides -->
    <div class="w-full h-full flex transition-all duration-500"
        :style="{ transform: `translateX(${isRTL ? '' : '-'}${activeSlide * 100}%)` }">
        @foreach ($placeholderImages as $index => $image)
            <div class="flex-shrink-0 w-full h-full">
                <img src="{{ $image }}" class="w-full h-full object-cover pointer-events-none"
                    alt="Slide {{ $index + 1 }}" draggable="false">
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-2 rtl:space-x-reverse">
        @foreach ($placeholderImages as $index => $image)
            <button class="h-3 rounded-full border-0 transition-all duration-500"
                :class="activeSlide === {{ $index }} ? 'w-6 bg-primary' : 'w-3 bg-primary/20'"
                @click="activeSlide = {{ $index }}">
            </button>
        @endforeach
    </div>
</div>
