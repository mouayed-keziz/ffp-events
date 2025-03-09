@props(['event'])

<div class="grid md:grid-cols-3 gap-6">
    {{-- Main Content Column --}}
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">{{ $event['title'] }}</h2>
            <div class="prose max-w-none">
                {!! $event['content'] !!}
            </div>
        </div>
    </div>

    {{-- Sidebar Column --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6 space-y-6">
            {{-- Date Section --}}
            <div class="flex gap-2">
                @include('website.svg.event.callendar')
                <div>
                    <h3 class="text-sm text-neutral-600 mb-2">{{ __('website/event.event_date') }}</h3>
                    <p class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($event['start_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                        <span>-</span>
                    </p>
                    <p class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($event['end_date'])->locale(app()->getLocale())->isoFormat('LL') }}</p>
                </div>
            </div>

            {{-- Location Section --}}
            <div class="flex gap-2">
                @include('website.svg.event.location')
                <div>
                    <h3 class="text-sm text-neutral-600 mb-2">{{ __('website/event.location') }}</h3>
                    <p class="text-sm font-semibold">{{ $event['location'] }}</p>
                </div>
            </div>

            {{-- Registration Deadlines --}}
            <div class="flex gap-2">
                @include('website.svg.event.callendar')
                <div>
                    <h3 class="text-sm text-neutral-600 mb-2">{{ __('website/event.exhibitor_registration_date') }}</h3>
                    <p class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($event['exhibitor_registration_start_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                        <span>-</span>
                    </p>
                    <p class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($event['exhibitor_registration_end_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                @include('website.svg.event.callendar')
                <div>
                    <h3 class="text-sm text-neutral-600 mb-2">{{ __('website/event.visitor_registration_date') }}</h3>
                    <p class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($event['visitor_registration_start_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                        <span>-</span>
                    </p>
                    <p class="text-sm font-semibold">
                        {{ \Carbon\Carbon::parse($event['visitor_registration_end_date'])->locale(app()->getLocale())->isoFormat('LL') }}
                    </p>
                </div>
            </div>

            {{-- Website --}}
            <div class="flex gap-2">
                @include('website.svg.event.website')
                <div>
                    <h3 class="text-sm text-neutral-600 mb-2">{{ __('website/event.website') }}</h3>
                    <a href="{{ $event['website_url'] }}" target="_blank"
                        class="text-sm font-semibold hover:text-primary">
                        {{ $event['website_url'] }}
                    </a>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="flex gap-2">
                @include('website.svg.event.contact')
                <div>
                    <h3 class="text-sm text-neutral-600 mb-2">{{ __('website/event.project_manager_contact') }}</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="text-neutral-600">{{ __('website/event.name') }}:</span> <span
                                class="font-semibold">{{ $event['contact']['name'] }}</span></p>
                        <p><span class="text-neutral-600">{{ __('website/event.email') }}:</span> <a
                                href="mailto:amir.rabhi@ffp-events.com"
                                class="font-semibold hover:text-primary">{{ $event['contact']['email'] }}</a></p>
                        <p><span class="text-neutral-600">{{ __('website/event.phone') }}:</span> <a
                                href="tel:{{ $event['contact']['phone_number'] }}"
                                class="font-semibold hover:text-primary">{{ $event['contact']['phone_number'] }}</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col gap-3 pt-4">
                <a href="{{ route('visit_event', $event) }}"
                    class="btn text-base font-bold btn-outline border-base-200 border-2 normal-case">
                    {{ __('website/event.visit') }}
                </a>
                <a href="{{ route('exhibit_event', $event) }}" class="btn text-base font-bold btn-primary normal-case">
                    {{ __('website/event.exhibit_and_sponsor') }}
                </a>
            </div>
        </div>
    </div>
</div>
