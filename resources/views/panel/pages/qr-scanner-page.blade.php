<x-filament::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            @include('panel.components.scanner.qr-scanner')
        </div>

        <div>
            <x-scanner.result-state :state="$resultState" :errorMessage="$errorMessage" :successBlocks="$resultBlocks"
                title="{{ __('panel/scanner.results_section_title') }}"
                description="View detailed information about scanned badges" icon="heroicon-o-clipboard-document-list" />
        </div>
    </div>
</x-filament::page>
