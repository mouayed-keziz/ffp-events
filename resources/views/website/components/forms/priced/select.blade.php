@props(['data', 'answerPath'])

<div class="mb-4">
    <label class="label">
        <span
            class="label-text font-semibold text-[#546675] text-sm {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? ($data['label']['fr'] ?? '') }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span class="label-text-alt font-semibold text-[#83909B] text-sm pl-[10px]">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    @php
        $formData = data_get($this, 'formData.' . $answerPath, ['options' => []]);
        $options = $data['options'] ?? [];
        $selectedOption = collect($formData['options'] ?? [])->first(function ($option) {
            return $option['selected'] ?? false;
        });
    @endphp

    <div wire:key="select-{{ $answerPath }}" x-data="{
        open: false,
        selectedOption: @js($selectedOption),
    
        selectOption(option, optionValue, priceData) {
            this.selectedOption = {
                value: optionValue,
                price: priceData[$wire.preferred_currency] || 0
            };
            this.open = false;
            $wire.updateSelectOption('{{ $answerPath }}', optionValue);
        }
    }" class="relative">
        <button type="button" @click="open = !open"
            class="input input-bordered bg-white rounded-md w-full flex justify-between items-center pl-[10px]"
            :class="selectedOption ? '' : 'text-gray-500'">
            <div class="flex items-center gap-2 overflow-hidden">
                <span x-text="selectedOption ? selectedOption.value : '{{ __('Select an option') }}'"
                    class="truncate"></span>
                <template x-if="selectedOption && selectedOption.price !== null">
                    <span class="bg-primary/10 text-primary font-semibold text-sm px-3 py-1 rounded-md">
                        <span x-text="selectedOption.price"></span> <span x-text="$wire.preferred_currency"></span>
                    </span>
                </template>
            </div>
            <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open" @click.away="open = false" x-transition
            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 overflow-auto focus:outline-none pl-[10px]"
            style="display: none;">
            @foreach ($options as $option)
                @php
                    $optionLabel = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
                    $priceData = json_encode($option['price'] ?? []);
                @endphp
                <button type="button" @click="selectOption($event, '{{ $optionLabel }}', {{ $priceData }})"
                    class="w-full text-left px-4 py-2 hover:bg-gray-100"
                    :class="selectedOption && selectedOption.value === '{{ $optionLabel }}' ? 'bg-primary/10' : ''">
                    <div class="flex items-center gap-2">
                        <span>{{ $optionLabel }}</span>
                        @if (isset($option['price']))
                            @include('website.components.forms.priced.price-badge', [
                                'price' => $option['price'][$this->preferred_currency] ?? 0,
                                'currency' => $this->preferred_currency,
                            ])
                        @endif
                    </div>
                </button>
            @endforeach
        </div>
    </div>
</div>
