@props(['answerPath'])
@if (config('app.debug'))
    <div class="mt-2 p-2 bg-gray-100 text-xs rounded" x-data="{ open: false }">
        <div class="flex items-center gap-2 cursor-pointer" @click="open = !open">
            <p>Debug - Answer Path: {{ $answerPath }}</p>
            <span x-show="!open">Show</span>
            <span x-show="open">Hide</span>
        </div>
        <div x-show="open">
            <pre class="mt-2">@json(data_get($this, 'formData.' . $answerPath), JSON_PRETTY_PRINT)</pre>
        </div>
    </div>
@endif
