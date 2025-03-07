{{-- 
    Multi-step form partial that shows progress indicators and current step
    
    Required variables:
    - $steps: Array of steps data
    - $currentStep: Current active step index
    - $errors: Validation errors
    - $formSubmitted: Whether the form has been submitted 
    - $successMessage: Message to show on successful submission
--}}

<div>
    @if (session('error'))
        <div class="alert alert-error mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($formSubmitted)
        <div class="rounded-btn alert alert-success mb-4 shadow-md text-white">
            <x-heroicon-o-check-circle class="w-6 h-6 inline-block mr-2" />
            {{ $successMessage }}
        </div>
    @else
        <!-- Multi-step progress indicator -->
        <div class="mb-8">
            <div class="hidden sm:block">
                <div class="relative" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                    <!-- Progress bar container with connectors -->
                    <div class="relative">
                        <!-- Connector lines layer -->
                        <div class="absolute inset-x-0 top-4 flex justify-center items-center">
                            <div class="w-full mx-8 flex">
                                @for ($i = 0; $i < count($steps) - 1; $i++)
                                    <div class="flex-1 h-1 {{ $i < $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Circles and labels -->
                        <div class="grid relative z-10"
                            style="grid-template-columns: repeat({{ count($steps) }}, 1fr);">
                            @for ($i = 0; $i < count($steps); $i++)
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                                        <span class="text-white font-medium">{{ $i + 1 }}</span>
                                    </div>
                                    <div class="mt-2 px-2 w-full">
                                        <span
                                            class="text-sm text-center break-words inline-block w-full {{ $i === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                            {{ isset($steps[$i]['title']) ? $steps[$i]['title'] ?? ($steps[$i]['title'][app()->getLocale()] ?? ($steps[$i]['title']['fr'] ?? '')) : '' }}
                                        </span>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile progress indicator -->
            <div class="sm:hidden">
                <div class="flex items-center justify-between mb-2">
                    @for ($i = 0; $i < count($steps); $i++)
                        @if ($i > 0)
                            <div class="flex-1 h-1 {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                        @endif
                        <div
                            class="w-6 h-6 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                            <span class="text-white text-xs">{{ $i + 1 }}</span>
                        </div>
                    @endfor
                </div>
                <div class="text-center">
                    <span class="font-bold text-primary text-sm">
                        {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? ($steps[$currentStep]['title']['en'] ?? '')) : '' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Current step title and description -->
        <div class="mb-6">
            <h2 class="text-xl font-bold">
                {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'] ?? ($steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? '')) : '' }}
            </h2>
            @if (isset($steps[$currentStep]['description']) && !empty($steps[$currentStep]['description'][app()->getLocale()]))
                <p class="text-gray-600 mt-2">
                    {{ $steps[$currentStep]['description'][app()->getLocale()] }}
                </p>
            @endif
        </div>
    @endif
</div>
