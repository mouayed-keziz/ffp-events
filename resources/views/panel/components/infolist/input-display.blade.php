<div class="input-display">
    @php
        $inputType = $getState()['type'] ?? 'text';
        $value = $getState()['value'] ?? '';
        $isEmpty = empty($value) && $value !== '0';
        $borderClass = $isEmpty
            ? 'text-gray-500 dark:text-gray-400'
            : 'border-primary-600 bg-primary-600/10 dark:border-primary-500 dark:bg-primary-600/10';
    @endphp

    <div class="border {{ $borderClass }} rounded-md p-3 mt-1">
        @if ($isEmpty)
            <span class="text-gray-500 italic">No answer provided</span>
        @else
            @switch($inputType)
                @case('paragraph')
                    <div class="whitespace-pre-wrap">{{ $value }}</div>
                @break

                @case('date')
                    <div>{{ $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '' }}</div>
                @break

                @case('email')
                    <div>
                        <a href="mailto:{{ $value }}" class="text-primary-600 hover:underline">{{ $value }}</a>
                    </div>
                @break

                @case('phone')
                    <div>
                        <a href="tel:{{ $value }}" class="text-primary-600 hover:underline">{{ $value }}</a>
                    </div>
                @break

                @case('number')
                    <div>
                        {{-- {{ json_encode($value) }} --}}
                        {{ is_array($value) ? implode(', ', $value) : (is_numeric($value) ? number_format($value, is_float($value) ? 2 : 0) : $value) }}
                    </div>
                @break

                @default
                    {{-- {{ json_encode($value) }} --}}
                    <div>{{ $value }}</div>
            @endswitch
        @endif
    </div>
</div>
