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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- This is a placeholder for plan tiers. The actual implementation would load plans from the database --}}
        <div class="card bg-base-100 border border-gray-200 hover:border-primary hover:shadow-md transition-all">
            <div class="card-body">
                <h3 class="card-title">{{ __('Basic Plan') }}</h3>
                <div class="py-2">
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 1') }}
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 2') }}
                        </li>
                    </ul>
                </div>
                <div class="mt-2">
                    <p class="font-bold text-lg">10,000 DZD</p>
                </div>
                <div class="card-actions justify-end mt-4">
                    <input type="radio" class="radio radio-primary" name="plan_tier" value="basic"
                        wire:model.defer="formData.{{ $answerPath }}" />
                </div>
            </div>
        </div>
        
        <div class="card bg-base-100 border border-gray-200 hover:border-primary hover:shadow-md transition-all">
            <div class="card-body">
                <h3 class="card-title">{{ __('Standard Plan') }}</h3>
                <div class="py-2">
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 1') }}
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 2') }}
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 3') }}
                        </li>
                    </ul>
                </div>
                <div class="mt-2">
                    <p class="font-bold text-lg">20,000 DZD</p>
                </div>
                <div class="card-actions justify-end mt-4">
                    <input type="radio" class="radio radio-primary" name="plan_tier" value="standard"
                        wire:model.defer="formData.{{ $answerPath }}" />
                </div>
            </div>
        </div>
        
        <div class="card bg-base-100 border border-gray-200 hover:border-primary hover:shadow-md transition-all">
            <div class="card-body">
                <h3 class="card-title">{{ __('Premium Plan') }}</h3>
                <div class="py-2">
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 1') }}
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 2') }}
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 3') }}
                        </li>
                        <li class="flex items-center">
                            <x-heroicon-o-check class="w-5 h-5 text-green-500 mr-2" />
                            {{ __('Feature 4') }}
                        </li>
                    </ul>
                </div>
                <div class="mt-2">
                    <p class="font-bold text-lg">30,000 DZD</p>
                </div>
                <div class="card-actions justify-end mt-4">
                    <input type="radio" class="radio radio-primary" name="plan_tier" value="premium"
                        wire:model.defer="formData.{{ $answerPath }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <label class="label">
            <span class="label-text text-sm">{{ __('Quantity') }}</span>
        </label>
        <input type="number" min="1" class="input input-bordered w-full md:w-1/3" 
            wire:model.defer="formData.{{ str_replace('answer', 'quantity', $answerPath) }}" />
    </div>
</div>

{{-- TODO: Implement dynamic plan tier loading from the database:
    1. Fetch plan tiers based on the plan_tier_id in the field data
    2. Display actual features and prices from the database
    3. Add comparison functionality
--}}