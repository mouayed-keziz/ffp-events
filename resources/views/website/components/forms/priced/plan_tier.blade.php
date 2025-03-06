@props(['data', 'answerPath'])
{{-- {{ dd($data) }}     --}}
<div class="mb-4">
    <label class="label">
        <span class="label-text font-medium {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? ($data['label']['fr'] ?? '') }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span class="label-text-alt text-xs text-gray-500">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    <div>
        @if (isset($data['plan_tier_id']))
            @if (isset($data['plan_tier_details']['title']))
                <div class="font-medium text-lg mb-4">
                    {{ $data['plan_tier_details']['title'][app()->getLocale()] ??
                        ($data['plan_tier_details']['title']['fr'] ?? __('Plan Tier')) }}
                </div>
            @endif

            @if (isset($data['plan_tier_details']['plans']) && count($data['plan_tier_details']['plans']) > 0)
                @php
                    $selectedPlanId = $this->{$answerPath} ?? null;
                    $dir = app()->getLocale() === 'ar' ? 'rtl' : 'ltr';
                    $chevronClass = $dir === 'rtl' ? 'ms-1' : 'me-1';
                @endphp

                <div class="space-y-3">
                    @foreach ($data['plan_tier_details']['plans'] as $plan)
                        @php
                            $isSelected = $selectedPlanId == $plan['id'];
                        @endphp
                        <div x-data="{ expanded: false }"
                            class="w-full rounded-xl transition-all {{ $isSelected ? 'border-primary/60 bg-primary/10' : '' }}">
                            <!-- Plan option row with radio button -->
                            <div class="flex items-center justify-between py-3 px-3 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="radio" id="plan_{{ $plan['id'] }}" name="plan_selection"
                                        value="{{ $plan['id'] }}" wire:model="{{ $answerPath }}"
                                        class="radio radio-sm" />
                                    <label for="plan_{{ $plan['id'] }}" class="font-medium cursor-pointer">
                                        {{ $plan['title'] }}
                                    </label>

                                    <div>
                                        @include('website.components.forms.priced.price-badge', [
                                            'price' => $plan['price'][$this->preferred_currency ?? 'DZD'] ?? 0,
                                            'currency' => $this->preferred_currency ?? 'DZD',
                                        ])
                                    </div>
                                </div>

                                <button type="button" class="text-sm font-bold text-black hover:text-primary"
                                    x-on:click="expanded = !expanded" dir="{{ $dir }}">
                                    <span x-show="!expanded">
                                        @if ($dir === 'rtl')
                                            {{ __('See more') }}
                                            <x-heroicon-o-chevron-down
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                        @else
                                            <x-heroicon-o-chevron-down
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                            {{ __('See more') }}
                                        @endif
                                    </span>
                                    <span x-show="expanded">
                                        @if ($dir === 'rtl')
                                            {{ __('See less') }}
                                            <x-heroicon-o-chevron-up
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                        @else
                                            <x-heroicon-o-chevron-up
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                            {{ __('See less') }}
                                        @endif
                                    </span>
                                </button>
                            </div>

                            <!-- Expandable content -->
                            <div x-show="expanded" x-collapse
                                class="py-3 px-3 {{ $isSelected ? 'border-primary/60 bg-primary/10' : '' }}">
                                <div class="grid grid-cols-8 gap-4">
                                    <!-- Plan Image (3 columns) -->
                                    <div class="col-span-3">
                                        @if (isset($plan['image']) && $plan['image'])
                                            <img src="{{ $plan['image'] }}" alt="{{ $plan['title'] }}"
                                                class="w-full aspect-[55/43] h-auto rounded-lg object-cover">
                                        @else
                                            <div
                                                class="w-full aspect-[4/3] bg-gray-100 rounded-lg flex items-center justify-center">
                                                <x-heroicon-o-document-text class="w-12 h-12 text-gray-300" />
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Plan Content (5 columns) -->
                                    <div class="col-span-5">
                                        <div class="font-medium text-lg mb-2">
                                            {!! $plan['content'] !!}
                                        </div>

                                        @if (isset($plan['content']) && !empty($plan['content'][app()->getLocale()]))
                                            <div
                                                class="prose prose-sm max-w-none prose-headings:text-primary prose-headings:font-bold">
                                                {!! $plan['content'][app()->getLocale()] !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500 text-center py-4">
                    {{ __('No plans available') }}
                </div>
            @endif
        @else
            <div class="text-gray-500 text-center py-4">
                <x-heroicon-o-table-cells class="w-8 h-8 mx-auto mb-2 text-primary/50" />
                <p>{{ __('No plan tier selected') }}</p>
            </div>
        @endif
    </div>
</div>
