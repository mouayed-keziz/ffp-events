@props([
    'blocks' => [],
    'columns' => 2, // 2 columns by default for grid layout
])

@if (!empty($blocks))
    @php
        $gridBlocks = collect($blocks)->filter(fn($block) => ($block['layout'] ?? 'grid') === 'grid');
        $fullBlocks = collect($blocks)->filter(fn($block) => ($block['layout'] ?? 'grid') === 'full');
    @endphp

    <!-- Full-width blocks first -->
    @foreach ($fullBlocks as $block)
        <x-scanner.result-block :label="$block['label']" :data="$block['data']" :icon="$block['icon'] ?? null" :style="$block['style'] ?? 'default'"
            :type="$block['type'] ?? 'info'" :colSpan="2" />
    @endforeach

    <!-- Grid blocks -->
    @if ($gridBlocks->isNotEmpty())
        <div class="grid grid-cols-{{ $columns }} gap-3">
            @foreach ($gridBlocks as $block)
                <x-scanner.result-block :label="$block['label']" :data="$block['data']" :icon="$block['icon'] ?? null" :style="$block['style'] ?? 'default'"
                    :type="$block['type'] ?? 'info'" :colSpan="$block['colSpan'] ?? 1" />
            @endforeach
        </div>
    @endif
@endif
