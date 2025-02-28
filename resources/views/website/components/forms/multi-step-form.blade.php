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
                <div class="relative flex items-center justify-between">
                    @for ($i = 0; $i < count($steps); $i++)
                        <!-- Step indicator -->
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                                <span class="text-white font-medium">{{ $i + 1 }}</span>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="{{ $i <= $currentStep ? 'font-bold text-primary' : 'text-gray-700' }}">
                                    {{$steps[$i]['title']}}
                                    {{ isset($steps[$i]['title']) ? $steps[$i]['title'][app()->getLocale()] ?? ($steps[$i]['title']['fr'] ?? '') : '' }}
                                </span>
                            </div>
                        </div>

                        <!-- Connector line between steps (except after the last step) -->
                        @if ($i < count($steps) - 1)
                            <div class="flex-1 mx-2">
                                <div class="h-1 rounded-btn {{ $i < $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Mobile progress indicator (simplified) -->
            <div class="sm:hidden">
                <div class="relative flex items-center justify-between">
                    @for ($i = 0; $i < count($steps); $i++)
                        <!-- Step circle -->
                        <div
                            class="w-6 h-6 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                        </div>

                        <!-- Connector line between steps (except after the last step) -->
                        @if ($i < count($steps) - 1)
                            <div class="flex-1">
                                <div class="h-1 {{ $i < $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Display the current step's title and description -->
        <div class="mb-6">
            <h2 class="text-xl font-bold">
                {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? '' : '' }}
            </h2>
            @if (isset($steps[$currentStep]['description']) && !empty($steps[$currentStep]['description'][app()->getLocale()]))
                <p class="text-gray-600 mt-2">
                    {{ $steps[$currentStep]['description'][app()->getLocale()] }}
                </p>
            @endif
        </div>
    @endif
</div>