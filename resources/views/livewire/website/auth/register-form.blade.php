<?php

use Livewire\Volt\Component;
use App\Models\Visitor;

new class extends Component {
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $terms_accepted;
    public $errorMessage;

    public function register()
    {
        $this->resetErrorBag();

        $validated = $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:visitors,email|unique:users,email|unique:exhibitors,email',
            'password' => 'required|min:6|confirmed',
            'terms_accepted' => 'accepted',
        ]);
        unset($validated['terms_accepted']);

        $visitor = Visitor::create($validated);
        auth()->guard('visitor')->login($visitor);
        return redirect()->to(route('events'));
    }
}; ?>

<div class="flex justify-center mt-8">
    <div class="card w-full max-w-4xl shadow-lg bg-white rounded-lg p-6 pb-12">
        <h2 class="text-xl font-bold text-center mb-2">{{ __('website/register.create_account') }}</h2>
        <p class="text-center text-neutral-500 text-sm mb-6">
            {{ __('website/register.create_account_description') }}
        </p>
        <form wire:submit.prevent="register">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Nom Complet -->
                <div class="form-control">
                    <label class="label">
                        <span
                            class="label-text text-neutral-500 font-semibold text-xs">{{ __('website/register.full_name') }}</span>
                    </label>
                    <input type="text" name="name" wire:model="name"
                        class="input input-bordered w-full rounded-lg bg-white {{ $errors->has('name') ? 'input-error' : '' }}"
                        required placeholder="John Doe">
                    @error('name')
                        @include('website.components.global.input-error', ['message' => $message])
                    @enderror
                </div>
                <!-- Adresse mail -->
                <div class="form-control">
                    <label class="label">
                        <span
                            class="label-text text-neutral-500 font-semibold text-xs">{{ __('website/register.email') }}</span>
                    </label>
                    <input type="email" name="email" wire:model="email"
                        class="input input-bordered w-full rounded-lg bg-white {{ $errors->has('email') ? 'input-error' : '' }}"
                        required placeholder="test@example.com">
                    @error('email')
                        @include('website.components.global.input-error', ['message' => $message])
                    @enderror
                </div>
                <!-- Mot de passe -->
                @include('website.components.global.password-input', [
                    'name' => 'password',
                    'wireModel' => 'password',
                    'placeholder' => '••••••••••••••',
                    'label' => __('website/reset_password.password_label'),
                ])
                <!-- Répétez le mot de passe -->
                @include('website.components.global.password-input', [
                    'name' => 'password_confirmation',
                    'wireModel' => 'password_confirmation',
                    'placeholder' => '••••••••••••••',
                    'label' => __('website/reset_password.password_confirmation_label'),
                ])
            </div>

            <!-- Checkbox for Terms -->
            <div class="form-control my-8">
                <div class="flex justify-start items-center gap-2">
                    <input type="checkbox" name="terms_accepted" wire:model="terms_accepted"
                        class="checkbox rounded-lg {{ $errors->has('terms_accepted') ? 'checkbox-error' : '' }}">
                    <span class="label-text font-semibold text-neutral">
                        {{ __('website/register.terms_acceptance') }}
                        <a class="link link-primary">{{ __('website/register.terms_and_conditions') }}</a>
                    </span>
                </div>
                @error('terms_accepted')
                    @include('website.components.global.input-error', ['message' => $message])
                @enderror
            </div>

            <!-- Register Button -->
            <div class="form-control mb-6">
                <button type="submit"
                    class="btn btn-neutral w-full rounded-lg">{{ __('website/register.create_my_account') }}</button>
            </div>

            <!-- Alert Component -->
            <div class="alert bg-primary/20 text-red-500 mb-6 flex flex-row items-center" role="alert">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-start ml-2 text-xs font-semibold leading-relaxed">
                    {{ __('website/register.account_type_alert') }}
                    <a href="#" class="font-bold underline">{{ __('website/register.contact_team') }}</a>
                </span>
            </div>

            <!-- Already have an account -->
            <div class="text-center text-sm text-neutral-500">
                <span>{{ __('website/register.already_have_account') }}</span>
                <a href="{{ route('login') }}" class="link link-primary">{{ __('website/register.login') }}</a>
            </div>
        </form>
    </div>
</div>
