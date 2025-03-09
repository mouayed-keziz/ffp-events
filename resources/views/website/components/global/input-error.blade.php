@props(['message' => null])

@if ($message)
    <span class="label-text-alt text-error text-xs">{{ $message }}</span>
@endif
