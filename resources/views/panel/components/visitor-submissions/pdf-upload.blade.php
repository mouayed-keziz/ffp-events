{{-- PDF Upload Display Component --}}
<div class="space-y-2">
    <div class="flex items-center">
        <x-heroicon-o-document-text class="w-5 h-5" />
        {{ __('panel/visitor_submissions.pdf_file') }}
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ $fileUrl }}" class="text-primary-600 hover:text-primary-500 inline-flex items-center gap-1" target="_blank">
            <x-heroicon-o-eye class="w-5 h-5" />
            {{ __('panel/visitor_submissions.actions.open') }}
        </a>
        <a href="{{ $fileUrl }}" class="text-primary-600 hover:text-primary-500 inline-flex items-center gap-1" download>
            <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
            {{ __('panel/visitor_submissions.actions.download') }}
        </a>
    </div>
</div>