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
                    <!-- Daisy UI steps implementation -->
                    <ul class="steps w-full">
                        <!-- Exhibitor forms steps -->
                        @for ($i = 0; $i < count($steps); $i++)
                            <li class="step {{ $i <= $currentStep ? 'step-primary' : '' }}">
                                <span
                                    class="text-sm text-center {{ $i === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                    {{ isset($steps[$i]['title']) ? $steps[$i]['title'][app()->getLocale()] ?? ($steps[$i]['title']['fr'] ?? '') : '' }}
                                </span>
                            </li>
                        @endfor

                        <!-- Info Validation step -->
                        <li class="step {{ count($steps) <= $currentStep ? 'step-primary' : '' }}">
                            <span
                                class="text-sm text-center {{ count($steps) === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                {{ __('Info Validation') }}
                            </span>
                        </li>

                        <!-- Payment step -->
                        <li class="step {{ count($steps) + 1 <= $currentStep ? 'step-primary' : '' }}">
                            <span
                                class="text-sm text-center {{ count($steps) + 1 === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500 ' }}">
                                {{ __('Payment') }}
                            </span>
                        </li>

                        <!-- Payment Validation step -->
                        <li class="step {{ count($steps) + 2 <= $currentStep ? 'step-primary' : '' }}">
                            <span
                                class="text-sm text-center {{ count($steps) + 2 === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                {{ __('Payment Validation') }}
                            </span>
                        </li>

                        <!-- Post-payment form steps -->
                        @foreach ($postForms as $index => $form)
                            <li class="step {{ count($steps) + 3 + $index <= $currentStep ? 'step-primary' : '' }}">
                                <span
                                    class="text-sm text-center {{ count($steps) + 3 + $index === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                    {{ $form['title'][app()->getLocale()] ?? ($form['title']['fr'] ?? '') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Mobile progress indicator -->
            <div class="sm:hidden">
                <ul class="steps steps-horizontal w-full">
                    @for ($i = 0; $i < count($steps) + count($postForms) + 3; $i++)
                        <li class="step {{ $i <= $currentStep ? 'step-primary' : '' }}">
                            {{-- <span class="step-circle text-xs">{{ $i + 1 }}</span> --}}
                        </li>
                    @endfor
                </ul>
                <div class="text-center mt-2">
                    <span class="font-bold text-primary text-sm">
                        {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? ($steps[$currentStep]['title']['en'] ?? '')) : '' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Current step title and description -->
        <div class="mb-6">
            <h2 class="text-xl font-bold">
                {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? '') : '' }}
            </h2>
            @if (isset($steps[$currentStep]['description']) && !empty($steps[$currentStep]['description'][app()->getLocale()]))
                <p class="text-gray-600 mt-2">
                    {{ $steps[$currentStep]['description'][app()->getLocale()] }}
                </p>
            @endif
        </div>
    @endif
</div>
