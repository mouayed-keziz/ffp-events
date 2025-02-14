<?php

use Livewire\Volt\Component;

new class extends Component {
    // todo
}; ?>

<div class="flex justify-center mt-8"> <!-- Increased margin-top -->
    <div class="card w-full max-w-4xl shadow-lg bg-white rounded-lg p-6 pb-12">
        <h2 class="text-xl font-bold text-center mb-2">Créer un compte</h2>
        <p class="text-center text-neutral-500 text-sm mb-6">
            Créez votre compte aujourd’hui pour pouvoir s’inscrire à nos évènements et les visiter
        </p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Nom Complet -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-neutral-500 font-semibold text-xs">Nom Complet</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full rounded-lg bg-white" required placeholder="John Doe">
                </div>
                <!-- Adresse mail -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-neutral-500 font-semibold text-xs">Adresse mail</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered w-full rounded-lg bg-white" required placeholder="test@example.com">
                </div>
                <!-- Mot de passe -->
                <div class="form-control" x-data="{ showPassword: false }">
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
                <!-- Répétez le mot de passe -->
                <div class="form-control" x-data="{ showConfirmPassword: false }">
                    <label class="label">
                        <span class="label-text text-neutral-500 font-semibold text-xs">Répétez le mot de passe</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" class="input input-bordered w-full pr-10 rounded-lg bg-white" required placeholder="6+ characters">
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.944-9.543-7a10.05 10.05 0 011.563-3.18m3.116-2.19A9.969 9.969 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.134 5.42M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Checkbox for Terms -->
            <div class="form-control my-8">
                <div class="flex justify-start items-center gap-2">
                    <input type="checkbox" name="terms" class="checkbox rounded-lg">
                    <span class="label-text font-semibold text-neutral">
                        J’accèpte les <a class="link link-primary">termes et conditions d’utilisation</a>
                    </span>
                </div>
            </div>

            <!-- Register Button -->
            <div class="form-control mb-6">
                <button type="submit" class="btn btn-neutral w-full rounded-lg">Créer mon compte</button>
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
                    Le compte que vous allez créer est un compte visiteur, si vous voulez créer un compte exposant ou bien sponsor veuillez
                    <a href="#" class="font-bold underline">contacter l’équipe ffp events</a>
                </span>
            </div>

            <!-- Already have an account -->
            <div class="text-center text-sm text-neutral-500">
                <span>Vous avez déja un compte? </span>
                <a class="link link-primary">Connectez vous</a>
            </div>
        </form>
    </div>
</div>
