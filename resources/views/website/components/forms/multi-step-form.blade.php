{{-- 
    Multi-step form partial that shows progress indicators and current step
    
    Required variables:
    - $steps: Array of exhibitor form steps data
    - $postForms: Array of post-payment form data
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
                        <!-- Exhibitor initial forms steps -->
                        @foreach ($steps as $index => $step)
                            <li class="step {{ $index <= $currentStep ? 'step-primary' : '' }}">
                                <span
                                    class="text-sm text-center {{ $index === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                    {{ isset($step['title']) ? $step['title'][app()->getLocale()] ?? ($step['title']['fr'] ?? '') : '' }}
                                </span>
                            </li>
                        @endforeach

                        <!-- Fixed steps: Info Validation, Payment, Payment Validation -->
                        @php
                            $fixedSteps = [
                                'info_validation' => ['index' => count($steps), 'title' => __('Info Validation')],
                                'payment' => ['index' => count($steps) + 1, 'title' => __('Payment')],
                                'payment_validation' => [
                                    'index' => count($steps) + 2,
                                    'title' => __('Payment Validation'),
                                ],
                            ];
                        @endphp

                        @foreach ($fixedSteps as $step)
                            <li class="step {{ $step['index'] <= $currentStep ? 'step-primary' : '' }}">
                                <span
                                    class="text-sm text-center {{ $step['index'] === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                                    {{ $step['title'] }}
                                </span>
                            </li>
                        @endforeach

                        <!-- Post-payment form steps -->
                        @foreach ($postForms as $index => $form)
                            @php
                                $postFormIndex = count($steps) + 3 + $index;
                            @endphp
                            <li class="step {{ $postFormIndex <= $currentStep ? 'step-primary' : '' }}">
                                <span
                                    class="text-sm text-center {{ $postFormIndex === $currentStep ? 'font-bold text-gray-900' : 'text-gray-500' }}">
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
                    @php
                        $totalSteps = count($steps) + 3 + count($postForms); // Initial forms + 3 fixed steps + post forms
                    @endphp
                    @for ($i = 0; $i < $totalSteps; $i++)
                        <li class="step {{ $i <= $currentStep ? 'step-primary' : '' }}">
                            {{-- <span class="step-circle text-xs">{{ $i + 1 }}</span> --}}
                        </li>
                    @endfor
                </ul>
                <div class="text-center mt-2">
                    <span class="font-bold text-primary text-sm">
                        @if ($currentStep < count($steps))
                            {{ isset($steps[$currentStep]['title']) ? $steps[$currentStep]['title'][app()->getLocale()] ?? ($steps[$currentStep]['title']['fr'] ?? ($steps[$currentStep]['title']['en'] ?? '')) : '' }}
                        @elseif ($currentStep === count($steps))
                            {{ __('Info Validation') }}
                        @elseif ($currentStep === count($steps) + 1)
                            {{ __('Payment') }}
                        @elseif ($currentStep === count($steps) + 2)
                            {{ __('Payment Validation') }}
                        @else
                            @php
                                $postFormIndex = $currentStep - count($steps) - 3;
                            @endphp
                            {{ isset($postForms[$postFormIndex]['title']) ? $postForms[$postFormIndex]['title'][app()->getLocale()] ?? ($postForms[$postFormIndex]['title']['fr'] ?? '') : '' }}
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Current step title and description - show only for exhibitor form steps -->
        @if ($currentStep < count($steps) && isset($steps[$currentStep]))
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
    @endif
</div>
