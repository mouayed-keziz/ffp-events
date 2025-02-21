@props(['message' => null])

@if ($message)
    <span class="label-text-alt text-error text-xs mt-1">{{ $message }}</span>
@endif
