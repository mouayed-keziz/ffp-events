<?php

use function Livewire\Volt\{state};

state(['openItems' => [0]]); // First item open by default

$toggleItem = function ($index) {
    if (in_array($index, $this->openItems)) {
        $this->openItems = array_diff($this->openItems, [$index]);
    } else {
        $this->openItems[] = $index;
    }
};

$isOpen = function ($index) {
    return in_array($index, $this->openItems);
};

$faqs = [
    [
        'question' => 'How can I get the update?',
        'answer' => 'Donec id justo. Curabitur blandit mollis lacus. Vivamus quis mi. In ut quam vitae odio lacinia tincidunt. In consectetuer turpis ut velit.',
    ],
    [
        'question' => 'Which license is right for you?',
        'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    ],
    [
        'question' => 'How long is my license valid for?',
        'answer' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
    ],
    [
        'question' => 'Which platforms will the template support?',
        'answer' => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
    ],
    [
        'question' => 'For what kind of projects is the Standard license intended?',
        'answer' => 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ],
    [
        'question' => 'Do you have a free demo to review the code before purchasing?',
        'answer' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.',
    ],
];
?>

<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-10">
            <span class="text-sm font-medium text-primary uppercase tracking-wider">FAQS</span>
            <h2 class="text-3xl font-bold mt-2">Les questions les plus Fréquentes</h2>
        </div>

        <div class="space-y-4">
            @foreach ($faqs as $index => $faq)
                <div class="bg-white rounded-xl overflow-hidden">
                    <button wire:click="toggleItem({{ $index }})"
                        class="w-full flex justify-between items-center p-6 hover:bg-gray-50 transition-colors">
                        <span class="text-left font-medium">{{ $faq['question'] }}</span>
                        <span class="flex-shrink-0 ml-4">
                            @if ($isOpen($index))
                                @include('website.svg.minus')
                            @else
                                @include('website.svg.plus')
                            @endif
                        </span>
                    </button>

                    @if ($isOpen($index))
                        <div class="px-6 pb-6 text-gray-600">
                            {{ $faq['answer'] }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
