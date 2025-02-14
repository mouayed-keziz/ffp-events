<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="flex justify-center mt-16"> <!-- similar styling to login form -->
    <div class="card w-full max-w-md shadow-lg bg-white rounded-lg p-6">
        <h2 class="text-xl font-bold text-center mb-2">Récupérer mon compte</h2>
        <p class="text-center text-neutral-500 text-sm mb-6">
            Insérez l’email de votre compte pour recevoir le lien de récupération
        </p>
        <form method="POST" action="#">
            @csrf
            <!-- Email Input -->
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text text-neutral-500 font-semibold text-xs">Adresse mail</span>
                </label>
                <input type="email" name="email" class="input input-bordered w-full rounded-lg bg-white" required placeholder="test@example.com">
            </div>
            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="button" class="btn btn-outline text-[1rem] border-base-200 border-2">
                    Annuler
                </button>
                <button type="submit" class="btn btn-neutral flex-1">
                    Récupérer le compte
                </button>
            </div>
        </form>
    </div>
</div>
