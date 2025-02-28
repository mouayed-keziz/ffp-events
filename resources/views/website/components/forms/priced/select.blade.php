@props(['data', 'answerPath'])

<div class="mb-4">
    <label class="label">
        <span class="label-text font-medium {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? $data['label']['fr'] ?? '' }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span class="label-text-alt text-xs text-gray-500">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    <div x-data="{ 
        open: false, 
        selected: '', 
        selectedLabel: '', 
        selectedPrice: null,
        selectedCurrency: '{{$this->preferred_currency}}',
        options: {{ json_encode($data['options'] ?? []) }},
        
        setSelected(value, label, price) {
            this.selected = value;
            this.selectedLabel = label;
            this.selectedPrice = price;
            this.open = false;
            $wire.set('formData.{{ $answerPath }}', value);
        },
        
        init() {
            // Set initial value if already selected
            const initialValue = $wire.get('formData.{{ $answerPath }}');
            if (initialValue) {
                const option = this.options.find(opt => opt.option['{{ app()->getLocale() }}'] === initialValue);
                if (option) {
                    this.selectedLabel = option.option['{{ app()->getLocale() }}'] || option.option['fr'] || '';
                    this.selectedPrice = option.price ? option.price['DZD'] : null;
                    this.selected = initialValue;
                }
            }
        }
    }" class="relative">
        <button 
            type="button" 
            @click="open = !open" 
            class="input input-bordered bg-white rounded-md w-full flex justify-between items-center"
            :class="selected ? '' : 'text-gray-500'"
        >
            <div class="flex items-center gap-2 overflow-hidden">
                <span x-text="selectedLabel || '{{ __('Select an option') }}'" class="truncate"></span>
                <template x-if="selectedPrice !== null">
                    <span class="bg-primary/10 text-primary font-semibold text-sm px-3 py-1 rounded-md">
                        <span x-text="selectedPrice.toFixed(2)"></span> <span x-text="selectedCurrency"></span>
                    </span>
                </template>
            </div>
            <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <div 
            x-show="open" 
            @click.away="open = false" 
            x-transition
            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 overflow-auto focus:outline-none"
            style="display: none;"
        >
            @foreach ($data['options'] ?? [] as $option)
                <button
                    type="button"
                    @click="setSelected('{{ $option['option'][app()->getLocale()] ?? '' }}', '{{ $option['option'][app()->getLocale()] ?? $option['option']['fr'] ?? '' }}', {{ $option['price'][$this->preferred_currency] ?? 0 }})"
                    class="w-full text-left px-4 py-2 hover:bg-gray-100"
                >
                    <div class="flex items-center gap-2">
                        <span>{{ $option['option'][app()->getLocale()] ?? $option['option']['fr'] ?? '' }}</span>
                        @if (isset($option['price']))
                            @include('website.components.forms.priced.price-badge', [
                                'price' => $option['price'][$this->preferred_currency] ?? 0,
                                'currency' => $this->preferred_currency
                            ])
                        @endif
                    </div>
                </button>
            @endforeach
        </div>
        
        <input 
            type="hidden" 
            wire:model="formData.{{ $answerPath }}"
            x-bind:value="selected"
            {{ isset($data['required']) && $data['required'] ? 'required' : '' }}
        />
    </div>
</div>