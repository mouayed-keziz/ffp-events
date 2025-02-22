<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Validator;

new class extends Component {
    public $email;
    public $password;
    public $errorMessage;

    public function login()
    {
        $this->errorMessage = null;

        $this->resetErrorBag();
        $validator = Validator::make(
            [
                'email' => $this->email,
                'password' => $this->password,
            ],
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ],
        );

        if ($validator->fails()) {
            $validator->validate();
            return;
        }

        $credentials = ['email' => $this->email, 'password' => $this->password];

        if (auth()->guard('web')->attempt($credentials)) {
            redirect()->intended('/admin');
            return;
        }
        if (auth()->guard('exhibitor')->attempt($credentials)) {
            redirect()->intended(route('events'));
            return;
        }
        if (auth()->guard('visitor')->attempt($credentials)) {
            redirect()->intended(route('events'));
            return;
        }

        $this->errorMessage = __('website/login.incorrect_credentials');
    }
}; ?>

<div class="flex justify-center mt-16">
    <div class="card w-full max-w-md shadow-lg bg-white rounded-lg p-6">
        <h2 class="text-xl font-bold text-center mb-6">{{ __('website/login.title') }}</h2>
        <form wire:submit.prevent="login" method="POST">
            @csrf
            <div class="form-control mb-4">
                <label class="label">
                    <span
                        class="label-text text-neutral-500 font-semibold text-xs">{{ __('website/login.email_label') }}</span>
                </label>
                <input type="email" name="email"
                    class="input input-bordered w-full rounded-lg bg-white {{ $errors->has('email') ? 'input-error' : '' }}"
                    wire:model="email" required autofocus placeholder="test@example.com">
                @error('email')
                    @include('website.components.global.input-error', ['message' => $message])
                @enderror
                @if ($errorMessage)
                    @include('website.components.global.input-error', ['message' => $errorMessage])
                @endif
            </div>

            @include('website.components.global.password-input', [
                'name' => 'password',
                'wireModel' => 'password',
                'placeholder' => '••••••••••••••',
                'label' => __('website/reset_password.password_label'),
            ])

            <div class="text-right text-sm mb-6">
                <a href="{{ route('restore-account') }}"
                    class="link link-primary">{{ __('website/login.forgot_password') }}</a>
            </div>

            <div class="form-control mb-6">
                <button type="submit"
                    class="btn btn-neutral w-full rounded-lg">{{ __('website/login.login_button') }}</button>
            </div>

            <div class="text-center text-sm text-neutral-500">
                <span>{{ __('website/login.no_account') }}</span>
                <a href="{{ route('register') }}" class="link link-primary">{{ __('website/login.register_today') }}</a>
            </div>
        </form>
    </div>
</div>
