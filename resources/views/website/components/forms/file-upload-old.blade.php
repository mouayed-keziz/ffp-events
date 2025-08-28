@props(['data', 'answerPath', 'disabled' => false])
@php
    // Get existing file information if available
    $existingFile = null;
    $fieldAnswer = data_get($this->formData, $answerPath);
    $existingFileData = data_get($this->formData, str_replace('.answer', '.existing_file', $answerPath));

    if (!empty($fieldAnswer) && !empty($existingFileData)) {
        $existingFile = $existingFileData;
    }
@endphp
<div class="mb-8">
    <label class="block font-semibold text-[#546675] text-sm mb-2 {{ $disabled ? 'opacity-60' : '' }}">
        {{ $data['label'][app()->getLocale()] ?? __('website/forms.file_upload.label') }}
        @if ($data['required'] ?? false)
            <span class="text-error">*</span>
        @endif
    </label>
    <div x-data="{
        file: null,
        dragActive: false,
        dialogOpened: false,
        fileTypeError: false,
        fileType: '{{ $data['file_type'] ?? \App\Enums\FileUploadType::ANY }}',
        acceptedTypes: '{{ ($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::IMAGE ? 'image/*' : (($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::PDF ? 'application/pdf' : '*/*') }}',
        disabled: {{ $disabled ? 'true' : 'false' }},
        existingFile: @js($existingFile),
        showingExistingFile: @js(!empty($existingFile)),
    
        getFileTypeMessage() {
            if (this.fileType === '{{ \App\Enums\FileUploadType::IMAGE }}') {
                return '{{ __('website/forms.file_upload.image_only') }}';
            } else if (this.fileType === '{{ \App\Enums\FileUploadType::PDF }}') {
                return '{{ __('website/forms.file_upload.pdf_only') }}';
            } else {
                return '{{ __('website/forms.file_upload.any_file') }}';
            }
        },
    
        isValidFileType(file) {
            if (this.fileType === '{{ \App\Enums\FileUploadType::ANY }}') {
                return true;
            } else if (this.fileType === '{{ \App\Enums\FileUploadType::IMAGE }}') {
                return file.type.startsWith('image/');
            } else if (this.fileType === '{{ \App\Enums\FileUploadType::PDF }}') {
                return file.type === 'application/pdf';
            }
            return true;
        },
    
        triggerFileDialog() {
            if (this.dialogOpened || this.disabled) return;
            this.dialogOpened = true;
            setTimeout(() => {
                this.$refs.fileInput.click();
                this.dialogOpened = false;
            }, 0);
        },
    
        handleDrop(e) {
            if (this.disabled) return;
            e.preventDefault();
            this.dragActive = false;
            this.fileTypeError = false;
    
            if (e.dataTransfer.files.length) {
                const droppedFile = e.dataTransfer.files[0];
    
                if (this.isValidFileType(droppedFile)) {
                    this.file = droppedFile;
                } else {
                    this.fileTypeError = true;
                    // Clear the input
                    this.$refs.fileInput.value = '';
                }
            }
        },
    
        handleFileChange(e) {
            if (this.disabled) return;
            this.fileTypeError = false;
    
            if (e.target.files.length) {
                const selectedFile = e.target.files[0];
    
                if (this.isValidFileType(selectedFile)) {
                    this.file = selectedFile;
                } else {
                    this.fileTypeError = true;
                    // Clear the input
                    e.target.value = '';
                    // Also clear the Livewire model
                    @this.set('formData.{{ $answerPath }}', null);
                }
            }
        },
    
        removeFile() {
            if (this.disabled) return;
            this.file = null;
            this.showingExistingFile = false;
            this.$refs.fileInput.value = '';
            @this.set('formData.{{ $answerPath }}', null);
        },

        startUpdate() {
            if (this.disabled) return;
            this.showingExistingFile = false;
            this.file = null;
            this.$refs.fileInput.value = '';
            // Clear the existing file but don't set to null yet - user might cancel
            this.$nextTick(() => {
                this.triggerFileDialog();
            });
        },

        cancelUpdate() {
            if (this.disabled) return;
            this.showingExistingFile = true;
            this.file = null;
            this.$refs.fileInput.value = '';
        }
    }" class="relative {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">
        <input type="file" wire:model="formData.{{ $answerPath }}"
            accept="{{ ($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::IMAGE ? 'image/*' : (($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::PDF ? 'application/pdf' : '*/*') }}"
            x-ref="fileInput" class="hidden" @change="handleFileChange($event)"
            @if ($data['required'] ?? false) required @endif {{ $disabled ? 'disabled' : '' }} />

        <div @click="!disabled && (!showingExistingFile || !existingFile) && triggerFileDialog()"
            @dragover.prevent="!disabled && (dragActive = true)" @dragleave.prevent="dragActive = false"
            @drop="!disabled && handleDrop($event)"
            :class="{
                'border-2 border-primary': dragActive && !disabled,
                'border-2 border-error': fileTypeError,
                'opacity-60 cursor-not-allowed hover:bg-base-100/50': disabled,
                'hover:bg-base-100 cursor-pointer': !disabled && (!showingExistingFile || !existingFile),
                'hidden': showingExistingFile && existingFile && !file
            }"
            class="w-full p-8 md:p-0 aspect-[3] bg-base-100/50 border border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center transition-all">

            @include('website.svg.upload', [
                'class' => 'transition-opacity ' . ($disabled ? 'opacity-60' : ''),
            ])

            <p class="md:text-xl font-bold mt-2 {{ $disabled ? 'text-gray-500' : '' }}"
                x-text="fileTypeError ? '{{ __('website/forms.file_upload.invalid_file_type', ['type' => \App\Enums\FileUploadType::from($data['file_type'])->getLabel()]) }}' : '{{ __('website/forms.file_upload.drop_or_select') }}'">
            </p>

            <p class="text-xs md:text-sm" x-show="!fileTypeError">
                <span
                    class="{{ $disabled ? 'text-gray-500' : '' }}">{{ __('website/forms.file_upload.drop_here') }}</span>
                <a href="javascript:void(0)"
                    :class="disabled ? 'text-primary/60 cursor-not-allowed' : 'link link-primary hover:text-primary-focus'"
                    @click.prevent="!disabled && triggerFileDialog()">
                    {{ __('website/forms.file_upload.browse') }}
                </a>
            </p>

            <p class="text-xs md:text-sm text-error" x-show="fileTypeError" x-text="getFileTypeMessage()"></p>
        </div>

        <div x-show="!fileTypeError">
            <!-- Show existing file if present and no new file selected -->
            <template x-if="existingFile && showingExistingFile && !file">
                <div
                    class="flex justify-between items-center gap-4 my-2 py-2 px-4 rounded-btn font-semibold bg-green-50 border border-green-200">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-green-800" x-text="'• ' + existingFile?.fileName"></p>
                            <p class="text-xs text-green-600">{{ __('website/forms.file_upload.existing_file') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if (!$disabled)
                            <a :href="existingFile?.fileUrl" target="_blank" class="btn btn-sm btn-outline btn-info">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                {{ __('website/forms.file_upload.view') }}
                            </a>
                            <button type="button" @click="replaceExistingFile()" class="btn btn-sm btn-outline">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                {{ __('website/forms.file_upload.replace') }}
                            </button>
                            <button type="button" @click="removeFile()" class="btn btn-sm btn-outline btn-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                {{ __('website/forms.file_upload.remove') }}
                            </button>
                        @else
                            <a :href="existingFile?.fileUrl" target="_blank" class="btn btn-sm btn-outline btn-info">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                {{ __('website/forms.file_upload.view') }}
                            </a>
                        @endif
                    </div>
                </div>
            </template>

            <!-- Show update mode when no existing file is being shown and no new file is selected -->
            <template x-if="!showingExistingFile && !file && existingFile">
                <div class="flex justify-between items-center gap-4 my-2 py-2 px-4 rounded-btn font-semibold bg-yellow-50 border border-yellow-200">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-yellow-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <div>
                            <p class="text-yellow-800">{{ __('website/forms.file_upload.update_mode') }}</p>
                            <p class="text-xs text-yellow-600">{{ __('website/forms.file_upload.select_new_or_cancel') }}</p>
                        </div>
                    </div>
                    <button type="button" @click="cancelUpdate()"
                        class="btn btn-sm btn-outline btn-secondary">
                        {{ __('website/forms.file_upload.cancel') }}
                    </button>
                </div>
            </template>

            <!-- Show new file selection -->
            <template x-if="file">
                <div class="flex justify-between items-center gap-4 my-2 py-2 px-4 rounded-btn font-semibold"
                    :class="{
                        'bg-base-100/50 hover:bg-base-100 border border-dashed border-gray-300': !disabled,
                        'bg-base-100/30 border border-dashed border-gray-200 opacity-60': disabled
                    }">
                    <div class="flex items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6" :class="{ 'text-gray-400': disabled }">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <div>
                            <p x-text="'• ' + file?.name" :class="{ 'text-gray-500': disabled }"></p>
                            <p class="text-xs text-blue-600" x-text="existingFile ? '{{ __('website/forms.file_upload.replacing_file') }}' : '{{ __('website/forms.file_upload.new_file') }}'"></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <template x-if="existingFile">
                            <button type="button" @click="cancelUpdate()"
                                class="btn btn-sm btn-outline btn-secondary">
                                {{ __('website/forms.file_upload.cancel') }}
                            </button>
                        </template>
                        <button type="button" @click="removeFile()"
                            :class="{
                                'btn-error hover:bg-error-focus': !disabled,
                                'btn-disabled bg-gray-200 border-gray-300': disabled
                            }"
                            class="btn btn-square btn-sm p-1">
                            <svg class="text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
