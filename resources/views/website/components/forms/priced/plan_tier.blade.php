@props(['data', 'answerPath', 'disabled' => false])
<div class="mb-4">
    <label class="label">
        <span
            class="label-text font-semibold text-[#546675] text-sm {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? ($data['label']['fr'] ?? '') }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span
                class="label-text-alt font-semibold text-[#83909B] text-sm {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    <div>
        @if (isset($data['plan_tier_id']))
            @if (isset($data['plan_tier_details']['title']))
                <div class="font-semibold text-[#546675] text-lg mb-4">
                    {{ $data['plan_tier_details']['title'] ?? __('Plan Tier') }}
                </div>
            @endif

            @if (isset($data['plan_tier_details']['plans']) && count($data['plan_tier_details']['plans']) > 0)
                @php
                    // Initialize plans array in answer structure if it doesn't exist
// Prepend formData. to the answerPath when accessing data
$plansData = data_get($this, 'formData.' . $answerPath . '.plans', []);
if (empty($plansData)) {
    // Initialize the answer with all available plans
    $plansData = collect($data['plan_tier_details']['plans'])
        ->map(function ($plan) {
            return [
                'plan_id' => $plan['id'],
                'selected' => false,
                'price' => $plan['price'] ?? [],
            ];
        })
        ->toArray();

    // Set the initial plans data in the model - prepend formData. to the path
    data_set($this, 'formData.' . $answerPath . '.plans', $plansData);
}

$dir = app()->getLocale() === 'ar' ? 'rtl' : 'ltr';
$chevronClass = $dir === 'rtl' ? 'ms-1' : 'me-1';
                @endphp

                <div class="space-y-3 {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">
                    @foreach ($data['plan_tier_details']['plans'] as $planIndex => $plan)
                        @php
                            // Find the corresponding plan in our answer structure
                            $planAnswerIndex = -1;
                            $isSelected = false;

                            foreach (data_get($this, 'formData.' . $answerPath . '.plans', []) as $idx => $planData) {
                                if ($planData['plan_id'] == $plan['id']) {
                                    $planAnswerIndex = $idx;
                                    $isSelected = !empty($planData['selected']) && $planData['selected'] === true;
                                    break;
                                }
                            }
                        @endphp

                        <div x-data="{ expanded: false }"
                            class="w-full rounded-xl transition-all {{ $isSelected ? 'border-2 border-primary/70 bg-primary/10' : '' }} {{ $disabled ? 'opacity-70' : '' }}">
                            <!-- Plan option row with radio button -->
                            <div class="flex items-center justify-between py-3 px-3 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <input type="radio" id="plan_{{ $plan['id'] }}" name="plan_selection"
                                        value="{{ $plan['id'] }}"
                                        wire:model.live="formData.{{ $answerPath }}.plans.{{ $planAnswerIndex }}.selected"
                                        wire:change="updatePlanSelection('{{ $answerPath }}', '{{ $plan['id'] }}')"
                                        {{ $isSelected ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}
                                        class="radio radio-sm radio-primary" />
                                    <label for="plan_{{ $plan['id'] }}"
                                        class="font-semibold text-[#546675] cursor-pointer">
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
                                    x-on:click="expanded = !expanded" dir="{{ $dir }}"
                                    {{ $disabled ? 'disabled' : '' }}>
                                    <span x-show="!expanded">
                                        @if ($dir === 'rtl')
                                            {{ __('website/forms.plan_tier.see_more') }}
                                            <x-heroicon-o-chevron-down
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                        @else
                                            <x-heroicon-o-chevron-down
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                            {{ __('website/forms.plan_tier.see_more') }}
                                        @endif
                                    </span>
                                    <span x-show="expanded">
                                        @if ($dir === 'rtl')
                                            {{ __('website/forms.plan_tier.see_less') }}
                                            <x-heroicon-o-chevron-up
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                        @else
                                            <x-heroicon-o-chevron-up
                                                class="w-4 h-4 inline-block {{ $chevronClass }}" />
                                            {{ __('website/forms.plan_tier.see_less') }}
                                        @endif
                                    </span>
                                </button>
                            </div>

                            <!-- Expandable content -->
                            <div x-show="expanded" x-collapse class="py-3 px-3 {{ $isSelected ? '' : '' }}">
                                <div class="grid grid-cols-8 gap-4">
                                    <!-- Plan Image (3 columns) -->
                                    <div class="col-span-8 md:col-span-3">
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
                                    <div class="col-span-8 md:col-span-5">
                                        <div class="font-medium text-lg mb-2">
                                            {{ $plan['title'] }}
                                        </div>

                                        @php
                                            $currentLocale = app()->getLocale();
                                            $content = isset($plan['content']) ? $plan['content'] : '';
                                            // $content = isset($plan['content'])
                                            //     ? $plan['content'][$currentLocale] ?? ($plan['content']['fr'] ?? '')
                                            //     : '';
                                        @endphp
                                        @if (!empty($content))
                                            <div
                                                class="prose prose-sm max-w-none prose-headings:text-primary prose-headings:font-bold">
                                                {!! $content !!}
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
