@php
    $banners = \App\Models\Banner::where('is_active', true)->orderBy('order')->get();

    // Fallback to placeholder images if no banners are available
    $placeholderImages = [
        'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=2070&auto=format&fit=crop',
        'https://www.cvent.com/sites/default/files/image/2019-08/blog-topic-header-connect.jpg',
        'https://vancouver.websummit.com/wp-media/2024/06/Centre-Stage-during-the-opening-night-of-Web-Summit-Rio-2023-at-Riocentro-in-Rio-de-Janeiro-Brazil.jpg',
        'https://arabfounders.net/wp-content/uploads/2024/12/Web-Summit-Qatar-2025.webp',
    ];

    if ($banners->isEmpty()) {
        $images = collect($placeholderImages)->map(function ($url) {
            return (object) ['url' => null, 'image_url' => $url];
        });
    } else {
        $images = $banners->map(function ($banner) {
            return (object) [
                'url' => $banner->url,
                'image_url' => $banner->getFirstMediaUrl('banner') ?: url('/placeholder_wide.png'),
            ];
        });
    }
@endphp

<div x-data="{
    activeSlide: 0,
    slides: {{ count($images) }},
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
        @foreach ($images as $index => $image)
            <div class="flex-shrink-0 w-full h-full relative">
                <img src="{{ $image->image_url }}" class="w-full h-full object-cover pointer-events-none"
                    alt="Slide {{ $index + 1 }}" draggable="false">

                @if ($image->url)
                    <a href="{{ $image->url }}" target="_blank" rel="noopener noreferrer"
                        class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-black/40 hover:bg-black/60 text-white p-2 rounded-full transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>

                    </a>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-2 rtl:space-x-reverse">
        @foreach ($images as $index => $image)
            <button class="h-3 rounded-full border-0 transition-all duration-500"
                :class="activeSlide === {{ $index }} ? 'w-6 bg-primary' : 'w-3 bg-primary/20'"
                @click="activeSlide = {{ $index }}">
            </button>
        @endforeach
    </div>
</div>
