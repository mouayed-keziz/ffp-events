{{-- Other File Types Upload Display Component --}}
<div class="space-y-2">
    <div class="flex items-center gap-2">
        <x-heroicon-o-document class="w-5 h-5" />
        <span>{{ $fileType ?? __('panel/visitor_submissions.file_not_specified') }}</span>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ $fileUrl }}" class="text-primary-600 hover:text-primary-500 inline-flex items-center gap-1" download>
        <x-heroicon-o-arrow-down-tray class="w-5 h-5" />
            {{ __('panel/visitor_submissions.actions.download') }}
        </a>
    </div>
</div>