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

    <div class="bg-white border rounded-lg p-4">
        @if(isset($data['plan_tier_id']))
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-medium">{{ __('Plan Tier') }}</div>
                    <div class="text-xs text-gray-500">ID: {{ $data['plan_tier_id'] }}</div>
                    <input type="hidden" wire:model="{{ $answerPath }}" value="{{ $data['plan_tier_id'] }}" />
                </div>
                
                <div class="flex items-center">
                    <label class="text-xs text-gray-500 me-2">{{ __('Qty') }}:</label>
                    <input 
                        type="number" 
                        min="1" 
                        wire:model="{{ Str::replaceLast('.answer', '.quantity', $answerPath) }}"
                        class="input input-bordered input-xs w-16 text-center" 
                    />
                </div>
            </div>
            
            <div class="mt-4 bg-primary/10 p-3 rounded-md">
                <div class="flex justify-between text-sm">
                    <span class="font-medium">{{ __('Price') }}:</span>
                    <span class="font-bold text-primary">
                        {{ number_format($data['price'][Livewire::getComponent($this->_instance)->preferred_currency] ?? 0, 2) }} 
                        {{ Livewire::getComponent($this->_instance)->preferred_currency }}
                    </span>
                </div>
            </div>
        @else
            <div class="text-gray-500 text-center py-4">
                <x-heroicon-o-table-cells class="w-8 h-8 mx-auto mb-2 text-primary/50" />
                <p>{{ __('No plan tier selected') }}</p>
            </div>
        @endif
    </div>
</div>