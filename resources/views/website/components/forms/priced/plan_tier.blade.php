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
                    @if(isset($data['plan_tier_details']['title']))
                        <div class="font-medium text-lg">
                            {{ $data['plan_tier_details']['title'][app()->getLocale()] ?? 
                               $data['plan_tier_details']['title']['fr'] ?? 
                               __('Plan Tier') }}
                        </div>
                    @else
                        <div class="font-medium">{{ __('Plan Tier') }}</div>
                    @endif
                    <div class="text-xs text-gray-500">ID: {{ $data['plan_tier_id'] }}</div>
                    <input type="hidden" wire:model="{{ $answerPath }}" value="{{ $data['plan_tier_id'] }}" />
                </div>
                
                <div class="flex items-center">
                    <label class="text-xs text-gray-500 me-2">{{ __('Qty') }}:</label>
                    <div class="join">
                        <button type="button"
                            class="join-item btn btn-xs btn-outline"
                            wire:click="$set('{{ Str::replaceLast('.answer', '.quantity', $answerPath) }}', 
                                Math.max(1, parseInt({{ Str::replaceLast('.answer', '.quantity', $answerPath) }} || 1) - 1))"
                        >-</button>
                        
                        <input 
                            type="number" 
                            min="1" 
                            wire:model.live="{{ Str::replaceLast('.answer', '.quantity', $answerPath) }}"
                            class="join-item input input-xs input-bordered w-16 text-center" 
                        />
                        
                        <button type="button"
                            class="join-item btn btn-xs btn-outline"
                            wire:click="$set('{{ Str::replaceLast('.answer', '.quantity', $answerPath) }}', 
                                parseInt({{ Str::replaceLast('.answer', '.quantity', $answerPath) }} || 1) + 1)"
                        >+</button>
                    </div>
                </div>
            </div>
            
            @if(isset($data['plan_tier_details']['plans']) && count($data['plan_tier_details']['plans']) > 0)
                <div class="mt-4 space-y-3">
                    <div class="font-medium text-sm text-gray-600">{{ __('Available Plans:') }}</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($data['plan_tier_details']['plans'] as $plan)
                            <div class="border rounded-md p-3 bg-gray-50">
                                @if(isset($plan['image']) && $plan['image'])
                                    <div class="mb-2 h-24 bg-gray-200 rounded overflow-hidden">
                                        <img src="{{ $plan['image'] }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                
                                <div class="font-medium">
                                    {{ $plan['title'][app()->getLocale()] ?? $plan['title']['fr'] ?? 'Plan' }}
                                </div>
                                
                                @if(isset($plan['content']) && !empty($plan['content'][app()->getLocale()]))
                                    <div class="text-xs text-gray-600 line-clamp-2 my-1">
                                        {{ $plan['content'][app()->getLocale()] }}
                                    </div>
                                @endif
                                
                                <div class="mt-2">
                                    @include('website.components.forms.priced.price-badge', [
                                        'price' => $plan['price'][$this->preferred_currency] ?? 0,
                                        'currency' => $this->preferred_currency,
                                        'quantity' => $this->formData[Str::replaceLast('.answer', '.quantity', $answerPath)] ?? 1
                                    ])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="mt-4 bg-primary/10 p-3 rounded-md">
                <div class="flex justify-between text-sm">
                    <span class="font-medium">{{ __('Price') }}:</span>
                    <span class="font-bold text-primary">
                        @php
                            $planPrice = 0;
                            if (isset($data['price'][$this->preferred_currency])) {
                                $planPrice = $data['price'][$this->preferred_currency];
                            } elseif (isset($data['plan_tier_details']['plans'][0]['price'][$this->preferred_currency])) {
                                $planPrice = $data['plan_tier_details']['plans'][0]['price'][$this->preferred_currency];
                            }
                            $quantity = $this->formData[Str::replaceLast('.answer', '.quantity', $answerPath)] ?? 1;
                        @endphp
                        
                        {{ number_format($planPrice * $quantity, 2) }} 
                        {{ $this->preferred_currency }}
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