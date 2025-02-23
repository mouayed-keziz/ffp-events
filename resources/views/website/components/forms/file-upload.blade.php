@props(['wireModel' => null, 'fileType' => \App\Enums\FileUploadType::ANY])

<div class="mb-8">
    <label class="block text-gray-700 mb-2">{{ __('website/forms.file_upload.label') }}</label>
    <div x-data="{
        file: null,
        dragActive: false,
        dialogOpened: false,
        triggerFileDialog() {
            console.log('!مستفزة');
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
            if (e.dataTransfer.files.length) {
                this.file = e.dataTransfer.files[0];
            }
        },
        handleFileChange(e) {
            if (e.target.files.length) {
                this.file = e.target.files[0];
            }
        }
    }" class="relative">
        <input type="file" wire:model="{{ $wireModel }}"
            accept="{{ $fileType === \App\Enums\FileUploadType::IMAGE ? 'image/*' : ($fileType === \App\Enums\FileUploadType::PDF ? 'application/pdf' : '*/*') }}"
            x-ref="fileInput" class="hidden" @change="handleFileChange($event)" />
        <div @click="triggerFileDialog()" @dragover.prevent="dragActive = true" @dragleave.prevent="dragActive = false"
            @drop="handleDrop($event)"
            class="w-full aspect-[3] bg-base-100/50 hover:bg-base-100 border border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer transition-all"
            :class="{ 'border-2 border-primary': dragActive }">
            @include('website.svg.upload')
            <p class="text-xl font-bold mt-2">{{ __('website/forms.file_upload.drop_or_select') }}</p>
            <p class="text-sm">
                {{ __('website/forms.file_upload.drop_here') }}
                <a href="javascript:void(0)" class="link link-primary"
                    @click.prevent="triggerFileDialog()">{{ __('website/forms.file_upload.browse') }}
                </a>.
            </p>
        </div>
        <template x-if="file">
            <div
                class='flex justify-start items-center gap-4 my-2 py-2 px-4 rounded-btn font-semibold bg-base-100/50 hover:bg-base-100 border border-dashed border-gray-300'>
                <x-heroicon-o-document-text class="w-6 h-6" />
                <p x-text="'• ' + file?.name"></p>
            </div>
        </template>
    </div>
</div>
