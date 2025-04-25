<?php

use Livewire\Volt\Component;
use App\Settings\CompanyInformationsSettings;
use App\Models\Share;

new class extends Component {
    public $title;
    public $description;
    public $instagramLink;
    public $url;
    public $model;
    public $id;
    public function mount($title = '', $description = '', $url = '', $model = null, $id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        // Get instagram link from settings
        $settings = app(CompanyInformationsSettings::class);
        $this->instagramLink = $settings->instagramLink;
        $this->model = $model;
    }

    protected function recordShare($platform)
    {
        if ($this->model) {
            $this->model->shares()->create(['platform' => $platform]);
        }
    }

    public function isRtl()
    {
        return in_array(app()->getLocale(), ['ar', 'arabic']);
    }

    public function shareToFacebook()
    {
        $this->recordShare('facebook');
        // Open in new tab instead of redirecting
        $url = 'https://www.facebook.com/dialog/share?app_id=87741124305&display=popup&href=' . urlencode($this->url);
        $this->js("window.open('$url', '_blank')");
    }

    public function shareToInstagram()
    {
        $this->recordShare('instagram');
        // Open in new tab instead of redirecting
        $url = $this->instagramLink ?? 'https://www.instagram.com/';
        $this->js("window.open('$url', '_blank')");
    }

    public function shareToLinkedin()
    {
        $this->recordShare('linkedin');
        // Open in new tab instead of redirecting
        $url = 'https://www.linkedin.com/feed/?shareActive=true&shareUrl=' . urlencode($this->url);
        $this->js("window.open('$url', '_blank')");
    }

    public function shareToTwitter()
    {
        $this->recordShare('twitter');
        // Open in new tab instead of redirecting
        $url = 'https://twitter.com/intent/tweet?url=' . urlencode($this->url);
        $this->js("window.open('$url', '_blank')");
    }
}; ?>

<div x-data="{
    open: false,
    copyToClipboard() {
        // Record share via Livewire
        {{-- $wire.recordShare('copy'); --}}
        // Get copy button element
        const copyBtn = document.getElementById('copy-url-btn');
        const originalInnerHTML = copyBtn.innerHTML;

        // Copy URL to clipboard
        navigator.clipboard.writeText('{{ $url }}')
            .then(() => {
                copyBtn.classList.add('copied');
                copyBtn.innerHTML = `<svg xmlns='http://www.w3.org/2000/svg\' class='h-5 w-5\' viewBox='0 0 20 20' fill='currentColor'>
                                        <path fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8
                                            12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd' />
                                    </svg>`;

                setTimeout(() => {
                    copyBtn.classList.remove('copied');
                    copyBtn.innerHTML = originalInnerHTML;
                }, 2000);
            })
            .catch(err => {
                console.error('Could not copy text: ', err);
            });
    }
}" class="relative">
    <!-- Main share button -->
    <button @click="open = !open"
        class="btn btn-primary btn-circle btn-md md:btn-md shrink-0 z-20 relative hover:scale-105 transition-transform duration-200">
        @include('website.svg.share.share')
    </button>

    <!-- Social media buttons container -->
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="{{ $this->isRtl() ? 'md:left-full md:right-auto' : 'md:right-full md:left-auto' }} 
            absolute bottom-full md:bottom-0 flex flex-col md:flex-row items-center gap-1 md:gap-0.5 p-1 z-10">

        <!-- Facebook -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-600 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-blue-50 transition-all"
            style="transition-delay: 120ms;" wire:click="shareToFacebook">
            @include('website.svg.share.facebook')
        </button>

        <!-- Instagram -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-gray-800 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-gray-50 transition-all"
            style="transition-delay: 80ms;" wire:click="shareToInstagram">
            @include('website.svg.share.instagram', ['id' => $id])
        </button>

        <!-- LinkedIn -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-700 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-blue-50 transition-all"
            style="transition-delay: 40ms;" wire:click="shareToLinkedin">
            @include('website.svg.share.linkedin')
        </button>

        <!-- X -->
        <button x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-blue-700 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-blue-50 transition-all"
            style="transition-delay: 40ms;" wire:click="shareToTwitter">
            @include('website.svg.share.x')
        </button>

        <!-- Copy URL -->
        <button id="copy-url-btn" x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            x-transition:enter-end="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-x-0 translate-y-0 scale-90"
            x-transition:leave-end="opacity-0 {{ $this->isRtl() ? 'md:-translate-x-4' : 'md:translate-x-4' }} -translate-y-4 md:translate-y-0 scale-50"
            class="btn btn-circle btn-md md:btn-md bg-white text-gray-800 shadow-md transform scale-90 mb-1 md:mb-0 md:mx-0.5 hover:scale-100 hover:bg-gray-50 transition-all copy-btn"
            style="transition-delay: 0ms;" @click="copyToClipboard()">
            @include('website.svg.share.copy')
        </button>
    </div>
</div>
<script>
    function handleClick(e) {
        console.log("event", e);
        // Now you can access the event object (e) directly
    }
</script>
