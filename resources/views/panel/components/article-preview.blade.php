@php
    $hasImage = $getRecord()->hasMedia('image');
    $imageUrl = $hasImage ? $getRecord()->getFirstMediaUrl('image') : null;
    $content = $getRecord()->content;
@endphp

{{-- <x-filament::section> --}}
{{-- <x-slot name="heading"> --}}
{{-- {{ $getRecord()->title }} --}}
{{-- </x-slot> --}}

@if ($hasImage)
    <div class="shadow"
        style="position: relative; width: 100%; aspect-ratio: 16/9; border-radius: 0.5rem; overflow: hidden;">
        <img src="{{ $imageUrl }}" alt="{{ $getRecord()->title }}"
            style="width: 100%; height: 100%; object-fit: cover;">
    </div>
    <br /><br />
@else
    <p class="text-lg text-gray-500 dark:text-gray-400">{{ __('panel/articles.empty_states.image') }}</p>
    <br />
@endif

<div class="prose prose-sm sm:prose-md lg:prose-lg dark:prose-invert prose-dark max-w-none">

    {{-- <h2 class="font-bold">`{{ $getRecord()->title }}`</h2> --}}
    {!! str($content)->sanitizeHtml() !!}
</div>

{{-- </x-filament::section> --}}
