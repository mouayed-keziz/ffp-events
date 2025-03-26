<?php

use Livewire\Volt\Component;
use Illuminate\Support\Str;
use App\Helpers\PasswordResetHelper;
use Illuminate\Support\Facades\App;

new class extends Component {
    // Added property for email input.
    public $email;

    // Method to handle reset request.
    public function submit()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $model = null;
        $name = null;
        $userId = null;

        if ($user = \App\Models\User::where('email', $this->email)->first()) {
            $model = 'user';
            $name = $user->name;
            $userId = $user->id;
        } elseif ($user = \App\Models\Exhibitor::where('email', $this->email)->first()) {
            $model = 'exhibitor';
            $name = $user->name;
            $userId = $user->id;
        } elseif ($user = \App\Models\Visitor::where('email', $this->email)->first()) {
            $model = 'visitor';
            $name = $user->name;
            $userId = $user->id;
        } else {
            session()->flash('error', "Aucun compte n'a été trouvé");
            return;
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $this->email,
            ],
            [
                'token' => $token,
                'created_at' => now(),
            ],
        );

        // Use the new PasswordResetHelper to send localized reset email
        PasswordResetHelper::sendResetEmail(email: $this->email, token: $token, userId: $model, locale: App::getLocale(), name: $name);

        return redirect()->route('email-sent', ['email' => $this->email]);
    }
}; ?>

<div class="flex justify-center mt-16">
    <div class="card w-full max-w-md shadow-lg bg-white rounded-lg p-6">
        <h2 class="text-xl font-bold text-center mb-2">{{ __('website/restore_account.title') }}</h2>
        <p class="text-center text-neutral-500 text-sm mb-6">
            {{ __('website/restore_account.description') }}
        </p>
        <!-- Updated form: using wire:submit.prevent and binding the email input -->
        <form wire:submit.prevent="submit">
            @csrf
            <!-- Email Input -->
            <div class="form-control mb-6">
                <label class="label">
                    <span
                        class="label-text text-neutral-500 font-semibold text-xs">{{ __('website/restore_account.email_label') }}</span>
                </label>
                <input type="email" name="email" wire:model="email"
                    class="input input-bordered w-full rounded-lg bg-white" required placeholder="test@example.com">
                <!-- Session Message -->
                @if (session('error') || session('message'))
                    @include('website.components.global.input-error', [
                        'message' => session('error') ?? session('message'),
                    ])
                @endif
            </div>
            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('login') }}" type="button"
                    class="btn btn-outline text-[1rem] border-base-200 border-2">
                    {{ __('website/restore_account.cancel_button') }}
                </a>
                <button type="submit" class="btn btn-neutral flex-1">
                    {{ __('website/restore_account.submit_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
