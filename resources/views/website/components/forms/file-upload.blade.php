@props(['data', 'answerPath'])
<div class="mb-8">
    <label class="block text-gray-700 mb-2">
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
        fileType: '{{ ($data['file_type'] ?? \App\Enums\FileUploadType::ANY) }}',
        acceptedTypes: '{{ ($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::IMAGE ? 'image/*' : (($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::PDF ? 'application/pdf' : '*/*') }}',

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
            if (this.dialogOpened) return;
            this.dialogOpened = true;
            setTimeout(() => {
                this.$refs.fileInput.click();
                this.dialogOpened = false;
            }, 0);
        },

        handleDrop(e) {
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
        }
    }" class="relative">
        <input type="file"
            wire:model="formData.{{ $answerPath }}"
            accept="{{ ($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::IMAGE ? 'image/*' : (($data['file_type'] ?? \App\Enums\FileUploadType::ANY) === \App\Enums\FileUploadType::PDF ? 'application/pdf' : '*/*') }}"
            x-ref="fileInput"
            class="hidden"
            @change="handleFileChange($event)"
            @if ($data['required'] ?? false) required @endif />

        <div @click="triggerFileDialog()" @dragover.prevent="dragActive = true" @dragleave.prevent="dragActive = false"
            @drop="handleDrop($event)"
            class="w-full p-8 md:p-0 aspect-[3] bg-base-100/50 hover:bg-base-100 border border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer transition-all"
            :class="{ 'border-2 border-primary': dragActive, 'border-2 border-error': fileTypeError }">
            @include('website.svg.upload')
            <p class="md:text-xl font-bold mt-2" x-text="fileTypeError ? '{{ __('website/forms.file_upload.invalid_file_type', ["type" => \App\Enums\FileUploadType::from($data['file_type'])->getLabel()]) }}' : '{{ __('website/forms.file_upload.drop_or_select') }}'"></p>
            <p class="text-xs md:text-sm" x-show="!fileTypeError">
                {{ __('website/forms.file_upload.drop_here') }}
                <a href="javascript:void(0)" class="link link-primary"
                    @click.prevent="triggerFileDialog()">{{ __('website/forms.file_upload.browse') }}
                </a>.
            </p>
            <p class="text-xs md:text-sm text-error" x-show="fileTypeError" x-text="getFileTypeMessage()"></p>
        </div>

        <div x-show="!fileTypeError">
            <template x-if="file">
                <div
                    class='flex justify-start items-center gap-4 my-2 py-2 px-4 rounded-btn font-semibold bg-base-100/50 hover:bg-base-100 border border-dashed border-gray-300'>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <p x-text="'• ' + file?.name"></p>
                </div>
            </template>
        </div>
    </div>

    @error("formData.{{ $answerPath }}")
        <div class="text-error text-sm md:text-sm mt-1">{{ $message }}</div>
    @enderror
</div>
