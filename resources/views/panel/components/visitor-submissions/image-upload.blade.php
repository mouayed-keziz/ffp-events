{{-- Image Upload Display Component --}}
<div class="space-y-2">
    <img src="{{ $fileUrl }}" class="max-w-sm rounded-lg shadow-md" alt="{{ $fileName }}" />
    <div class="flex items-center gap-2">
        <a href="{{ $fileUrl }}" class="text-primary-600 hover:text-primary-300 inline-flex items-center gap-1"
            target="_blank">
            <x-heroicon-o-document-text class="w-5 h-5" />
            {{ __('panel/visitor_submissions.actions.view') }}
        </a>
        <a href="{{ $fileUrl }}" class="text-primary-600 hover:text-primary-300 inline-flex items-center gap-1"
            download>
            <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
            {{ __('panel/visitor_submissions.actions.download') }}
        </a>
    </div>
</div>
