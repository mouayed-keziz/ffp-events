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
                <!-- Circles and connector lines -->
                <div class="flex justify-between items-center mb-2">
                    <!-- Step indicators and connector lines in correct order -->
                    @for ($i = 0; $i < count($steps); $i++)
                        @if ($i > 0)
                            <div class="flex-1 h-1 rounded-btn {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                        @endif
                        
                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                            <span class="text-white font-medium">{{ $i + 1 }}</span>
                        </div>
                    @endfor
                </div>
                
                <!-- Step titles properly aligned with circles -->
                <div class="flex justify-between">
                    @for ($i = 0; $i < count($steps); $i++)
                        <div class="text-center w-8 {{ $i < count($steps)-1 ? 'flex-1' : '' }}">
                            <span class="{{ $i <= $currentStep ? 'font-bold text-primary' : 'text-gray-700' }} text-sm">
                                {{$steps[$i]['title']}}
                                {{ isset($steps[$i]['title']) ? $steps[$i]['title'][app()->getLocale()] ?? ($steps[$i]['title']['fr'] ?? ($steps[$i]['title']['en'] ?? '')) : '' }}
                            </span>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Mobile progress indicator (simplified) -->
            <div class="sm:hidden">
                <div class="flex items-center justify-between mb-2">
                    @for ($i = 0; $i < count($steps); $i++)
                        @if ($i > 0)
                            <div class="flex-1 h-1 {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}"></div>
                        @endif
                        
                        <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $i <= $currentStep ? 'bg-primary' : 'bg-gray-300' }}">
                            <span class="text-white text-xs">{{ $i + 1 }}</span>
                        </div>
                    @endfor
                </div>
                <!-- Current step title on mobile -->
                <div class="text-center">
                    <span class="font-bold text-primary text-sm">
                        {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? ($steps[$currentStep]['title']['en'] ?? '')) : '' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Display the current step's title and description -->
        <div class="mb-6">
            <h2 class="text-xl font-bold">
                {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? ($steps[$currentStep]['title']['en'] ?? '')) : '' }}
            </h2>
            @if (isset($steps[$currentStep]['description']) && !empty($steps[$currentStep]['description'][app()->getLocale()]))
                <p class="text-gray-600 mt-2">
                    {{ $steps[$currentStep]['description'][app()->getLocale()] }}
                </p>
            @endif
        </div>
    @endif
</div>