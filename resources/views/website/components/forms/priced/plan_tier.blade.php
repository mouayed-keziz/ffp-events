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

    <div class="p-4 border border-dashed rounded-lg bg-gray-50 text-center">
        <div class="text-gray-500">
            <x-heroicon-o-table-cells class="w-12 h-12 mx-auto mb-2 text-primary/50" />
            <p class="font-medium">{{ __('Plan Tier Component') }}</p>
            <p class="text-sm mt-1">{{ __('This feature is under development') }}</p>
        </div>

        <div class="mt-4">
            <p class="text-xs text-gray-400">TODO: Implement dynamic plan tier loading from the database</p>
        </div>
    </div>
</div>