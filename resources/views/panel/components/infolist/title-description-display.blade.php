<div class="field-title-description mt-6 -mb-4">
    @if (isset($getState()['title']))
        <div class="text-md font-semibold mb-1">{{ $getState()['title'] }} :</div>
    @endif

    @if (isset($getState()['description']) && !empty($getState()['description']))
        <div class="text-gray-500 text-sm mb-3">
            {{ $getState()['description'] }}
        </div>
    @endif
</div>
