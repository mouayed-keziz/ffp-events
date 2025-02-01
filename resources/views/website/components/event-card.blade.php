@props(['event'])

<div class="bg-white rounded-xl overflow-hidden shadow-sm py-2">
    <div class="grid md:grid-cols-2 gap-0">
        <div class="p-6 space-y-4 md:order-1 order-2">
            <h3 class="text-xl font-bold">{{ $event['title'] }}</h3>

            <div class="space-y-2">
                <div
                    class="flex items-center gap-2 badge bg-base-100 text-neutral-600 rounded-btn py-4 border-transparent">
                    @include('website.svg.location')
                    <span>{{ $event['location'] }}</span>
                </div>

                <div
                    class="flex items-center gap-2 badge bg-base-100 text-neutral-600 rounded-btn py-4 border-transparent">
                    @include('website.svg.calendar')
                    <span>{{ $event['dateStart'] }} au {{ $event['dateEnd'] }}</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="#"
                    class="btn text-[1rem] font-bold btn-outline border-base-200 border-2 flex-1 normal-case">VISITER</a>
                <a href="#" class="btn text-[1rem] font-bold btn-primary flex-1 normal-case">EXPOSER ET
                    SPONSORISER</a>
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
