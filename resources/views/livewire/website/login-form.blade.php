<?php

use Livewire\Volt\Component;

new class extends Component {
    // todo
}; ?>

<div class="flex justify-center mt-16"> <!-- Increased margin-top -->
    <div class="card w-full max-w-md shadow-lg bg-white rounded-lg p-6">
        <h2 class="text-xl font-bold text-center mb-6">Connectez vous à votre compte</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Input -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text text-neutral-500 font-semibold text-xs">Adresse mail</span>
                </label>
                <input type="email" name="email" class="input input-bordered w-full rounded-lg bg-white" required autofocus placeholder="test@example.com">
            </div>

            <!-- Password Input with AlpineJS -->
            <div class="form-control mb-2" x-data="{ showPassword: false }">
                <label class="label">
                    <span class="label-text text-neutral-500 font-semibold text-xs">Mot de passe</span>
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" name="password" class="input input-bordered w-full pr-10 rounded-lg bg-white" required placeholder="6+ characters">
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.944-9.543-7a10.05 10.05 0 011.563-3.18m3.116-2.19A9.969 9.969 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.134 5.42M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Forgotten password link -->
            <div class="text-right text-sm mb-6">
                <a  class="link link-primary">Mot de passe oublié?</a>
            </div>

            <!-- Login button -->
            <div class="form-control mb-6">
                <button type="submit" class="btn btn-neutral w-full rounded-lg">Se connecter</button>
            </div>

            <!-- Register link -->
            <div class="text-center text-sm text-neutral-500">
                <span>Vous n’avez pas de compte? </span>
                <a  class="link link-primary">Inscrivez vous aujourd’hui</a>
            </div>
        </form>
    </div>
</div>
