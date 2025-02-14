@php
    $events = [
        [
            'id' => 1,
            'title' => 'Cuisine Bain 3ème édition',
            'location' => 'Palais des expositions de la SAFEX (Pavilion Ahaggar, Casbah) Alger',
            'dateStart' => '20 Septembre 2025',
            'dateEnd' => '23 Septembre 2025',
            'countdown' => [
                'days' => 126,
                'hours' => 18,
                'minutes' => 45,
                'seconds' => '07',
            ],
            'image' => 'https://placehold.co/600x400',
        ],
        [
            'id' => 2,
            'title' => 'SIFFP 7ème édition',
            'location' => 'Palais des expositions de la SAFEX (Pavilion Ahaggar, Casbah) Alger',
            'dateStart' => '20 Septembre 2025',
            'dateEnd' => '23 Septembre 2025',
            'countdown' => [
                'days' => 126,
                'hours' => 18,
                'minutes' => 45,
                'seconds' => '07',
            ],
            'image' => 'https://placehold.co/600x400',
        ],
        [
            'id' => 3,
            'title' => 'Franchise Expo',
            'location' => 'Palais des expositions de la SAFEX (Pavilion Ahaggar, Casbah) Alger',
            'dateStart' => '20 Septembre 2025',
            'dateEnd' => '23 Septembre 2025',
            'countdown' => [
                'days' => 126,
                'hours' => 18,
                'minutes' => 45,
                'seconds' => '07',
            ],
            'image' => 'https://placehold.co/600x400',
        ],
    ];
@endphp

<section class="space-y-6">
    <h2 class="text-2xl font-bold">{{ __('website/home.events.title') }}</h2>

    <div class="space-y-4">
        @foreach ($events as $event)
            @include('website.components.event-card', ['event' => $event])
        @endforeach
    </div>
</section>
