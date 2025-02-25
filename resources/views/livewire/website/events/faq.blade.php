@php
    $faqs = app(\App\Settings\CompanyInformationsSettings::class)->faq;
@endphp

<div x-data="{ openItems: [0] }">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <span class="text-sm font-medium text-neutral-600 uppercase tracking-wider">FAQS</span>
                <h2 class="text-3xl font-bold">{{ __('website/home.faq.title') }}</h2>
            </div>

            <div class="space-y-4">
                @foreach ($faqs as $index => $faq)
                    <div class="bg-base-100 rounded-xl overflow-hidden">
                        <button
                            @click="openItems.includes({{ $index }}) ?
                                openItems = openItems.filter(i => i !== {{ $index }}) :
                                openItems.push({{ $index }})"
                            class="w-full flex justify-between items-center p-6 hover:bg-base-100/50 transition-colors">
                            <span class="text-left font-semibold text-lg">{{ $faq['question'] }}</span>
                            <span class="flex-shrink-0 ml-4">
                                <template x-if="openItems.includes({{ $index }})">
                                    <x-heroicon-o-minus class="w-5 h-5" />
                                </template>
                                <template x-if="!openItems.includes({{ $index }})">
                                    <x-heroicon-o-plus class="w-5 h-5" />
                                </template>
                            </span>
                        </button>

                        <div x-show="openItems.includes({{ $index }})" class="px-6 pb-6 text-gray-600">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
