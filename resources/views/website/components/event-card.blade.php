@props(['event', 'visitorSubmission' => null, 'exhibitorSubmission' => null])

@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\App;
@endphp

<div class="bg-white rounded-xl overflow-hidden shadow-sm py-2 relative">
    <pre>
</pre>
    <a href="{{ Auth::guard('web')->check() || Auth::guard('exhibitor')->check() || Auth::guard('visitor')->check()
        ? route('event_details', ['id' => $event['id']])
        : route('signin') }}"
        class="absolute inset-0 z-0"></a>
    <div class="grid md:grid-cols-2 gap-0 relative pointer-events-none">
        <div class="p-6 space-y-4 md:order-1 order-2">
            <h3 class="text-xl font-bold">{{ $event['title'] }}</h3>

            <div class="space-y-2">
                <div
                    class="flex items-center gap-2 font-light bg-base-100 text-neutral-600 rounded-btn py-1 px-3 border-transparent w-fit">
                    <div class="flex-shrink-0">
                        @include('website.svg.location')
                    </div>
                    <span class="flex-grow break-words">{{ $event['location'] }}</span>
                </div>

                <div
                    class="flex items-center gap-2 font-light bg-base-100 text-neutral-600 rounded-btn py-1 px-3 border-transparent w-fit">
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
                @if (Auth::guard('visitor')->check() && $visitorSubmission)
                    <button disabled
                        class="btn text-[1rem] font-bold btn-outline border-base-200 border-2 flex-1 normal-case btn-disabled opacity-60">
                        {{ __('website/home.events.already_registered') }}
                    </button>
                @elseif (
                    $event->is_visitor_registration_open &&
                        ((!Auth::guard('visitor')->check() && !Auth::guard('exhibitor')->check()) ||
                            (Auth::guard('visitor')->check() && !empty($event->visitorForm->sections))))
                    <a href="{{ Auth::guard('web')->check() || Auth::guard('exhibitor')->check() || Auth::guard('visitor')->check()
                        ? route('event_details', ['id' => $event['id']])
                        : route('signin') }}"
                        class="btn text-[1rem] font-bold btn-outline border-base-200 border-2 flex-1 normal-case">
                        {{ __('website/home.events.visit') }}
                    </a>
                @endif

                @if (Auth::guard('exhibitor')->check() && $exhibitorSubmission)
                    <button disabled
                        class="btn text-[1rem] font-bold btn-primary flex-1 normal-case btn-disabled opacity-60">
                        {{ __('website/home.events.already_registered') }}
                    </button>
                @elseif (
                    (!Auth::guard('visitor')->check() && !Auth::guard('exhibitor')->check()) ||
                        (Auth::guard('exhibitor')->check() && !empty($event->exhibitorForms->toArray())))
                    <a href="{{ Auth::guard('web')->check() || Auth::guard('exhibitor')->check() || Auth::guard('visitor')->check()
                        ? route('event_details', ['id' => $event['id']])
                        : route('signin') }}"
                        class="btn text-[1rem] font-bold btn-primary flex-1 normal-case">
                        {{ __('website/home.events.exhibit') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="relative h-full min-h-[200px] md:py-2 py-1 md:order-2 order-1">
            <div class="absolute inset-0 mx-2">
                <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}"
                    class="w-full h-full object-cover rounded-btn aspect-[7/4] md:aspect-video lg:aspect-[3/1]">
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                    @if ($event['countdown']['is_past'])
                        <div class="text-sm font-bold text-white bg-red-500 rounded-full px-3 py-1">
                            {{ __('website/event.is_past') }}
                        </div>
                    @elseif($event['countdown']['is_ongoing'])
                        @include('website.components.countdown', [
                            'countdown' => $event['countdown']['diff'],
                            'size' => 'md',
                        ])
                    @else
                        @include('website.components.countdown', [
                            'countdown' => $event['countdown']['diff'],
                            'size' => 'md',
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
