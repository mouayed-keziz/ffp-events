@props([
    'label' => '',
    'data' => '',
    'icon' => null,
    'style' => 'default', // default, highlight, success, warning, danger, info
    'type' => 'info', // info, card, badge, raw
    'colSpan' => 1, // 1, 2 (for grid layout)
])

@php
    $baseClasses = 'rounded-lg p-3';
    $styleClasses = match ($style) {
        'highlight'
            => 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4 flex-shrink-0',
        'success' => 'bg-green-50 dark:bg-green-800/50',
        'warning' => 'bg-yellow-50 dark:bg-yellow-800/50',
        'danger' => 'bg-red-50 dark:bg-red-800/50',
        'info' => 'bg-blue-50 dark:bg-blue-800/50',
        default => 'bg-gray-50 dark:bg-gray-800/50',
    };

    $gridClasses = $colSpan === 2 ? 'col-span-2' : '';
    $iconColorClasses = match ($style) {
        'highlight' => 'text-green-600 dark:text-green-400',
        'success' => 'text-green-600 dark:text-green-400',
        'warning' => 'text-yellow-600 dark:text-yellow-400',
        'danger' => 'text-red-600 dark:text-red-400',
        'info' => 'text-blue-600 dark:text-blue-400',
        default => 'text-gray-400 dark:text-gray-500',
    };

    $labelColorClasses = match ($style) {
        'highlight' => 'text-green-800 dark:text-green-200',
        'success' => 'text-green-900 dark:text-green-300',
        'warning' => 'text-yellow-900 dark:text-yellow-300',
        'danger' => 'text-red-900 dark:text-red-300',
        'info' => 'text-blue-900 dark:text-blue-300',
        default => 'text-gray-900 dark:text-white',
    };

    $dataColorClasses = match ($style) {
        'highlight' => 'text-green-700 dark:text-green-300',
        'success' => 'text-green-700 dark:text-green-300',
        'warning' => 'text-yellow-700 dark:text-yellow-300',
        'danger' => 'text-red-700 dark:text-red-300',
        'info' => 'text-blue-700 dark:text-blue-300',
        default => 'text-gray-600 dark:text-gray-300',
    };
@endphp

@if ($type === 'card')
    <!-- Card Type Block -->
    <div class="{{ $baseClasses }} {{ $styleClasses }} {{ $gridClasses }}">
        <div class="{{ $style === 'highlight' ? 'flex items-center space-x-3' : 'flex items-start space-x-2' }}">
            @if ($icon)
                <div class="flex-shrink-0">
                    @php($iconComponent = $icon)
                    <x-dynamic-component :component="$iconComponent"
                        class="{{ $style === 'highlight' ? 'h-6 w-6' : 'h-4 w-4 mt-0.5' }} {{ $iconColorClasses }} flex-shrink-0" />
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p
                    class="{{ $style === 'highlight' ? 'text-sm font-medium' : 'text-xs font-medium' }} {{ $labelColorClasses }} {{ $style === 'highlight' ? 'mb-0' : 'mb-1' }}">
                    {{ $label }}
                </p>
                <div
                    class="{{ $style === 'highlight' ? 'text-sm' : 'text-xs' }} {{ $dataColorClasses }} {{ $style === 'highlight' ? 'truncate' : '' }}">
                    {{ $data }}
                </div>
            </div>
        </div>
    </div>
@elseif($type === 'badge')
    <!-- Badge Type Block -->
    <div class="{{ $baseClasses }} {{ $styleClasses }} {{ $gridClasses }}">
        <div class="flex items-center space-x-2">
            @if ($icon)
                @php($iconComponent = $icon)
                <x-dynamic-component :component="$iconComponent" class="h-4 w-4 {{ $iconColorClasses }} flex-shrink-0" />
            @endif
            <div class="min-w-0 flex-1">
                <dt class="text-xs font-medium {{ $labelColorClasses }}">
                    {{ $label }}
                </dt>
                <dd class="text-xs {{ $dataColorClasses }}">
                    @if (is_string($data) && str_starts_with($data, '<span'))
                        {!! $data !!}
                    @else
                        {{ $data }}
                    @endif
                </dd>
            </div>
        </div>
    </div>
@elseif($type === 'raw')
    <!-- Raw Type Block (for code/data display) -->
    <div class="{{ $baseClasses }} {{ $styleClasses }} {{ $gridClasses }}">
        <div class="flex items-start space-x-2">
            @if ($icon)
                @php($iconComponent = $icon)
                <x-dynamic-component :component="$iconComponent" class="h-4 w-4 {{ $iconColorClasses }} mt-0.5 flex-shrink-0" />
            @endif
            <div class="min-w-0 flex-1">
                <dt class="text-xs font-medium {{ $labelColorClasses }} mb-1">
                    {{ $label }}
                </dt>
                <dd
                    class="text-xs {{ $dataColorClasses }} font-mono break-all bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded px-2 py-1">
                    {{ $data }}
                </dd>
            </div>
        </div>
    </div>
@else
    <!-- Default Info Type Block -->
    <div class="{{ $baseClasses }} {{ $styleClasses }} {{ $gridClasses }}">
        <div class="flex items-center space-x-2">
            @if ($icon)
                @php($iconComponent = $icon)
                <x-dynamic-component :component="$iconComponent" class="h-4 w-4 {{ $iconColorClasses }} flex-shrink-0" />
            @endif
            <div class="min-w-0 flex-1">
                <dt class="text-xs font-medium {{ $labelColorClasses }}">
                    {{ $label }}
                </dt>
                <dd class="text-xs {{ $dataColorClasses }} truncate">
                    {{ $data }}
                </dd>
            </div>
        </div>
    </div>
@endif
