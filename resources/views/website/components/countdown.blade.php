@php
    $size = $size ?? 'md';
    $classes = [
        'sm' => [
            'wrapper' => 'px-4 py-2',
            'numbers' => 'text-2xl',
            'labels' => 'text-xs',
            'gap' => 'gap-4',
        ],
        'md' => [
            'wrapper' => 'px-6 py-3',
            'numbers' => 'text-3xl',
            'labels' => 'text-xs',
            'gap' => 'gap-6',
        ],
        'lg' => [
            'wrapper' => 'px-4 md:px-6 py-3',
            'numbers' => 'text-2xl md:text-4xl',
            'labels' => 'text-xs md:text-sm',
            'gap' => 'gap-4 md:gap-6',
        ],
    ][$size];

    // Calculate total seconds from passed countdown
    $totalSeconds =
        ($countdown['years'] ?? 0) * 31536000 +
        ($countdown['months'] ?? 0) * 2592000 +
        $countdown['days'] * 86400 +
        $countdown['hours'] * 3600 +
        $countdown['minutes'] * 60 +
        $countdown['seconds'];
@endphp

<div x-data="{
    total: {{ $totalSeconds }},
    years: {{ $countdown['years'] ?? 0 }},
    months: {{ $countdown['months'] ?? 0 }},
    days: {{ $countdown['days'] }},
    hours: {{ $countdown['hours'] }},
    minutes: {{ $countdown['minutes'] }},
    seconds: {{ $countdown['seconds'] }},
    // Format numbers to add leading zero if less than 10
    formatNumber(num) {
        return (num < 10) ? '0' + num : num;
    },
    init() {
        setInterval(() => {
            if (this.total > 0) {
                this.total--;
                this.years = Math.floor(this.total / 31536000);
                this.months = Math.floor((this.total % 31536000) / 2592000);
                this.days = Math.floor((this.total % 2592000) / 86400);
                this.hours = Math.floor((this.total % 86400) / 3600);
                this.minutes = Math.floor((this.total % 3600) / 60);
                this.seconds = this.total % 60;
            }
        }, 1000);
    }
}" class="inline-block bg-black/10 backdrop-blur-sm rounded-xl {{ $classes['wrapper'] }}">
    <div class="flex {{ $classes['gap'] }} text-white text-center">
        <template x-if="years > 0">
            <div>
                <!-- Years -->
                <div class="font-bold {{ $classes['numbers'] }}" x-text="formatNumber(years)" :key="years"
                    x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-y-4"
                    x-transition:enter-end="translate-y-0">
                </div>
                <div class="{{ $classes['labels'] }}">{{ __('website/event.countdown.years') }}</div>
            </div>
        </template>
        <template x-if="months > 0">
            <div>
                <!-- Months -->
                <div class="font-bold {{ $classes['numbers'] }}" x-text="formatNumber(months)" :key="months"
                    x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-y-4"
                    x-transition:enter-end="translate-y-0">
                </div>
                <div class="{{ $classes['labels'] }}">{{ __('website/event.countdown.months') }}</div>
            </div>
        </template>
        <div>
            <!-- Days -->
            <div class="font-bold {{ $classes['numbers'] }}" x-text="formatNumber(days)" :key="days"
                x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-y-4"
                x-transition:enter-end="translate-y-0">
            </div>
            <div class="{{ $classes['labels'] }}">{{ __('website/event.countdown.days') }}</div>
        </div>
        <div>
            <!-- Hours -->
            <div class="font-bold {{ $classes['numbers'] }}" x-text="formatNumber(hours)" :key="hours"
                x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-y-4"
                x-transition:enter-end="translate-y-0">
            </div>
            <div class="{{ $classes['labels'] }}">{{ __('website/event.countdown.hours') }}</div>
        </div>
        <div>
            <!-- Minutes -->
            <div class="font-bold {{ $classes['numbers'] }}" x-text="formatNumber(minutes)" :key="minutes"
                x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-y-4"
                x-transition:enter-end="translate-y-0">
            </div>
            <div class="{{ $classes['labels'] }}">{{ __('website/event.countdown.minutes') }}</div>
        </div>
        <div>
            <!-- Seconds -->
            <div class="font-bold {{ $classes['numbers'] }}" x-text="formatNumber(seconds)" :key="seconds"
                x-transition:enter="transition transform duration-300" x-transition:enter-start="-translate-y-4"
                x-transition:enter-end="translate-y-0">
            </div>
            <div class="{{ $classes['labels'] }}">{{ __('website/event.countdown.seconds') }}</div>
        </div>
    </div>
</div>
