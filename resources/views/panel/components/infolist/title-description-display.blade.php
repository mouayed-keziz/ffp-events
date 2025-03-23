@php
    $state = $getState() ?? [];
    $title = isset($state['title'])
        ? (is_array($state['title'])
            ? json_encode($state['title'])
            : $state['title'])
        : null;
    $description = isset($state['description'])
        ? (is_array($state['description'])
            ? json_encode($state['description'])
            : $state['description'])
        : null;
@endphp

<div class="field-title-description mt-6 -mb-4">
    @if ($title)
        <div class="text-md font-semibold mb-1">{{ $title }} :</div>
    @endif

    @if ($description && !empty($description))
        <div class="text-gray-500 text-sm mb-3">
            {{ $description }}
        </div>
    @endif
</div>
