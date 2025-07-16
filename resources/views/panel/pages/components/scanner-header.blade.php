<div class="fi-header flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
            {{ __('panel/scanner.title') }}
        </h1>
        <p class="fi-header-subheading mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('panel/scanner.scanner_description') }}
        </p>
    </div>

    <div class="fi-header-actions flex items-center gap-3">
        <button id="startBtn" type="button" onclick="startScanner()"
            class="fi-btn fi-btn-color-success fi-btn-size-md inline-flex items-center gap-x-2 rounded-lg bg-green-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 dark:bg-green-500 dark:hover:bg-green-400">
            <x-heroicon-m-play class="h-4 w-4" />
            {{ __('panel/scanner.start_scanner') }}
        </button>

        <button id="stopBtn" type="button" onclick="stopScanner()" style="display: none;"
            class="fi-btn fi-btn-color-danger fi-btn-size-md inline-flex items-center gap-x-2 rounded-lg bg-red-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 dark:bg-red-500 dark:hover:bg-red-400">
            <x-heroicon-m-stop class="h-4 w-4" />
            {{ __('panel/scanner.stop_scanner') }}
        </button>
    </div>
</div>
