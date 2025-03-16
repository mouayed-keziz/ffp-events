<div>
    <div class="mb-2">
        <span class="font-medium">{{ $label }}</span>
    </div>

    @if ($type == 'upload')
        @if ($answer)
            <div class="text-sm rounded-lg bg-gray-50 p-2">
                <p class="text-sm text-gray-700">
                    {{ __('panel/visitor_submissions.file_uploaded_id') }}: {{ $answer }}
                </p>
            </div>
        @else
            <p class="text-sm text-gray-500">{{ __('panel/visitor_submissions.no_file_uploaded') }}</p>
        @endif
    @elseif($type == 'select' || $type == 'radio')
        @if ($answer)
            <div class="text-sm rounded-lg bg-gray-50 p-2">
                <p class="text-sm text-gray-700">{{ $answer }}</p>
            </div>
        @else
            <p class="text-sm text-gray-500">{{ __('panel/visitor_submissions.no_selection_made') }}</p>
        @endif
    @elseif($type == 'checkbox')
        @if ($answer)
            <div class="text-sm rounded-lg bg-gray-50 p-2">
                <p class="text-sm text-gray-700">{{ $answer }}</p>
            </div>
        @else
            <p class="text-sm text-gray-500">{{ __('panel/visitor_submissions.no_checkbox_selected') }}</p>
        @endif
    @else
        @if ($answer)
            <div class="text-sm rounded-lg bg-gray-50 p-2">
                <p class="text-sm text-gray-700">{{ $answer }}</p>
            </div>
        @else
            <p class="text-sm text-gray-500">{{ __('panel/visitor_submissions.no_answer_provided') }}</p>
        @endif
    @endif
</div>
