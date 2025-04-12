@props([
    'name' => 'password',
    'wireModel' => 'password',
    'placeholder' => null,
    'has_label' => true,
    'label' => __('website/reset_password.password_label'),
])
<div class="form-control mb-2" x-data="{ showPassword: false }">
    @if ($has_label)
        <label class="label">
            <span class="label-text text-neutral-500 font-semibold text-xs">{{ $label }}</span>
        </label>
    @endif
    <div class="relative">
        <input :type="showPassword ? 'text' : 'password'" name="{{ $name }}"
            class="input input-bordered w-full px-4 rounded-lg bg-white {{ $errors->has($name) ? 'input-error' : '' }}"
            wire:model="{{ $wireModel }}" required placeholder="{{ $placeholder }}">
        <button type="button" @click="showPassword = !showPassword"
            class="absolute inset-y-0 end-0 flex items-center px-3">
            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.944-9.543-7a10.05 10.05 0 011.563-3.18m3.116-2.19A9.969 9.969 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.134 5.42M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
            </svg>
        </button>
    </div>
    @error($name)
        @include('website.components.global.input-error', ['message' => $message])
    @enderror
</div>
