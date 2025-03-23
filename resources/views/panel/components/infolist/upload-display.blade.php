@php
    $answer = $getState()['answer'] ?? null;
    $media = $getState()['media'] ?? null;
    $fileName = $getState()['fileName'] ?? 'Unknown file';
    $fileUrl = $getState()['fileUrl'] ?? null;
    $fileType = $getState()['fileType'] ?? null;
    $isImage = $getState()['isImage'] ?? false;
    $isPdf = $getState()['isPdf'] ?? false;
    $fieldLabel = isset($getState()['fieldLabel'])
        ? (is_array($getState()['fieldLabel'])
            ? json_encode($getState()['fieldLabel'])
            : $getState()['fieldLabel'])
        : null;
@endphp

<div class="upload-display border rounded-lg overflow-hidden">
    @if (empty($answer))
        <div class="p-4 text-gray-500 dark:text-gray-400 italic">{{ __('panel/visitor_submissions.no_file_uploaded') }}
        </div>
    @elseif (!$fileUrl)
        <div class="p-4 text-gray-500 dark:text-gray-400 italic">{{ __('panel/visitor_submissions.file_not_found') }}
            (FileID: {{ $answer }})</div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow-sm">
            <!-- File header -->
            <div
                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex items-center justify-between">
                <div class="font-medium text-gray-900 dark:text-gray-100">
                    {{ $fileName }}
                </div>
                <a href="{{ $fileUrl }}" target="_blank"
                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm flex items-center gap-1">
                    <span>{{ __('Download') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <!-- File preview -->
            <div class="p-4">
                @if ($isImage)
                    <div class="flex justify-center">
                        <img src="{{ $fileUrl }}" alt="{{ $fileName }}"
                            class="max-w-full h-auto max-h-64 rounded border border-gray-200 dark:border-gray-600">
                    </div>
                @elseif ($isPdf)
                    <div
                        class="flex flex-col items-center justify-center border border-gray-200 dark:border-gray-600 rounded p-4 bg-gray-50 dark:bg-gray-700">
                        <svg class="text-gray-400 dark:text-gray-500 h-12 w-12 mb-2" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div class="text-center">
                            <p class="font-medium text-gray-700 dark:text-gray-300">{{ $fileName }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ __('panel/visitor_submissions.pdf_preview_available') }}</p>
                            <a href="{{ $fileUrl }}" target="_blank"
                                class="inline-flex items-center gap-1 mt-3 px-3 py-1 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors text-sm">
                                <span>{{ __('Open PDF') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                    <path
                                        d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center border border-gray-200 dark:border-gray-600 rounded p-4 bg-gray-50 dark:bg-gray-700">
                        <svg class="text-gray-400 dark:text-gray-500 h-12 w-12 mb-2" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <div class="text-center">
                            <p class="font-medium text-gray-700 dark:text-gray-300">{{ $fileName }}</p>
                            <a href="{{ $fileUrl }}" target="_blank"
                                class="inline-flex items-center gap-1 mt-3 px-3 py-1 bg-primary-500 text-white rounded-md hover:bg-primary-600 transition-colors text-sm">
                                <span>{{ __('Download file') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($fieldLabel)
                    <div class="mt-3 text-sm">
                        <span class="text-gray-500 dark:text-gray-400">{{ __('Field Label') }}:</span>
                        <span class="text-gray-700 dark:text-gray-300">{{ $fieldLabel }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
