@props(['event'])

<div class="bg-white rounded-xl overflow-hidden shadow-sm py-2 relative">
    <a href="{{ route('event_details', ['id' => $event['id']]) }}" class="absolute inset-0 z-0"></a>
    <div class="grid md:grid-cols-2 gap-0 relative pointer-events-none">
        <div class="p-6 space-y-4 md:order-1 order-2">
            <h3 class="text-xl font-bold">{{ $event['title'] }}</h3>

            <div class="space-y-2">
                <div
                    class="flex items-center gap-2 bg-base-100 text-neutral-600 rounded-btn py-1 px-3 border-transparent w-fit">
                    <div class="flex-shrink-0">
                        @include('website.svg.location')
                    </div>
                    <span class="flex-grow break-words">{{ $event['location'] }}</span>
                </div>

                <div
                    class="flex items-center gap-2 bg-base-100 text-neutral-600 rounded-btn py-1 px-3 border-transparent w-fit">
                    <div class="flex-shrink-0">
                        @include('website.svg.calendar')
                    </div>
                    <span class="flex-grow break-words">
                        {{ \Carbon\Carbon::parse($event['start_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                        {{ __('website/home.events.to') }}
                        {{ \Carbon\Carbon::parse($event['end_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pointer-events-auto">
                <a href="{{ route('event_details', ['id' => $event['id']]) }}"
                    class="btn text-[1rem] font-bold btn-outline border-base-200 border-2 flex-1 normal-case">
                    {{ __('website/home.events.visit') }}
                </a>
                <a href="{{ route('event_details', ['id' => $event['id']]) }}"
                    class="btn text-[1rem] font-bold btn-primary flex-1 normal-case">
                    {{ __('website/home.events.exhibit') }}
                </a>
            </div>
        </div>

        <div class="relative h-full min-h-[200px] md:py-2 py-1 md:order-2 order-1">
            <div class="absolute inset-0 mx-2">
                <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}"
                    class="w-full h-full object-cover rounded-btn aspect-[7/4] md:aspect-video lg:aspect-[3/1]">
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                    @include('website.components.countdown', [
                        'countdown' => $event['countdown'],
                        'size' => 'md',
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
