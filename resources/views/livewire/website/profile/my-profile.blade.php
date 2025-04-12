<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\EmailChangeHelper;

new class extends Component {
    public $name = '';
    public $email = '';
    public $newEmail = '';
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';
    public $locale = '';

    public function mount(): void
    {
        // Get the authenticated user (from any guard)
        $user = $this->getCurrentUser();
        if ($user) {
            $this->email = $user->email;
            $this->name = $user->name;
            $this->newEmail = $user->new_email ?? '';
            $this->locale = app()->getLocale();
        }
    }

    public function updateEmail(): void
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return;
        }

        $userType = $this->getUserType();

        $this->validate([
            'newEmail' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:visitors,email', 'unique:exhibitors,email'],
        ]);

        // Save the new email and generate a token
        $user->new_email = $this->newEmail;
        $user->save();

        // Generate a token for email verification
        $token = Str::random(64);

        // Store token in the password_reset_tokens table (reusing this table for email verification)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'created_at' => now(),
            ],
        );

        // Send verification email to the new email address
        EmailChangeHelper::sendVerificationEmail($this->newEmail, $token, $userType, $this->locale, $user->name);

        // session()->flash('success', __('profile.email_change_pending'));
    }

    public function updatePassword(): void
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return;
        }

        // Determine the guard to use for current_password validation
        $guard = 'web';
        if (Auth::guard('visitor')->check()) {
            $guard = 'visitor';
        } elseif (Auth::guard('exhibitor')->check()) {
            $guard = 'exhibitor';
        }

        $this->validate([
            'currentPassword' => ['required', 'current_password:' . $guard],
            'newPassword' => ['required', Password::defaults()],
            'newPasswordConfirmation' => ['required', 'same:newPassword'],
        ]);

        $user->password = $this->newPassword;
        $user->save();

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('password-success', __('website/profile.password_success'));
        $this->dispatch('password-updated');
    }

    protected function getCurrentUser()
    {
        // Check if user is authenticated in any guard
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        } elseif (Auth::guard('visitor')->check()) {
            return Auth::guard('visitor')->user();
        } elseif (Auth::guard('exhibitor')->check()) {
            return Auth::guard('exhibitor')->user();
        }

        return null;
    }

    protected function getUserType()
    {
        if (Auth::guard('web')->check()) {
            return 'user';
        } elseif (Auth::guard('visitor')->check()) {
            return 'visitor';
        } elseif (Auth::guard('exhibitor')->check()) {
            return 'exhibitor';
        }

        return null;
    }
}; ?>

<div class="space-y-8">
    <h2 class="text-2xl font-bold">{{ __('website/profile.my_profile', ['name' => $this->name]) }}</h2>

    <section class="bg-white rounded-lg shadow p-6 space-y-4">
        <h3 class="text-xl font-bold">{{ __('website/profile.update_email') }}</h3>

        @if (session('success'))
            <div class="alert alert-success text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit="updateEmail" class="space-y-4">
            <div class="form-control">
                <label for="email" class="label">
                    <span
                        class="label-text text-neutral-500 font-semibold text-xs">{{ __('website/profile.current_email') }}</span>
                </label>
                <input disabled id="email" type="email" value="{{ $email }}"
                    class="input input-bordered w-full px-4 rounded-lg bg-gray-100" required />
            </div>

            <div class="form-control">
                <label for="newEmail" class="label">
                    <span
                        class="label-text text-neutral-500 font-semibold text-xs">{{ __('website/profile.new_email') }}</span>
                </label>
                <input id="newEmail" type="email" wire:model="newEmail"
                    class="input input-bordered w-full px-4 rounded-lg bg-white {{ $errors->has('newEmail') ? 'input-error' : '' }}"
                    required />
                @error('newEmail')
                    @include('website.components.global.input-error', ['message' => $message])
                @enderror

                <div class="mt-2 text-xs text-gray-500 flex items-start gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 flex-shrink-0 mt-0.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    {{ __('profile.email_change_info') }}
                </div>
            </div>

            @if ($newEmail && $this->getCurrentUser() && $this->getCurrentUser()->new_email)
                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('profile.email_change_pending') }}</span>
                </div>
            @endif

            <div>
                <button type="submit" class="btn mt-4 font-semibold btn-sm rounded-md btn-primary">
                    {{ __('website/profile.update_email') }}
                </button>
            </div>
        </form>
    </section>

    <section class="bg-white rounded-lg shadow p-6 space-y-4 mt-6">
        <h3 class="text-xl font-bold">{{ __('website/profile.password_section_title') }}</h3>

        @if (session()->has('password-success'))
            <div class="alert alert-success text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('password-success') }}</span>
            </div>
        @endif

        <form wire:submit="updatePassword" class="space-y-4">
            @include('website.components.global.password-input', [
                'name' => 'currentPassword',
                'wireModel' => 'currentPassword',
                'label' => __('website/profile.current_password'),
                // 'has_label' => false,
            ])

            @include('website.components.global.password-input', [
                'name' => 'newPassword',
                'wireModel' => 'newPassword',
                'label' => __('website/profile.new_password'),
                // 'has_label' => false,
            ])

            @include('website.components.global.password-input', [
                'name' => 'newPasswordConfirmation',
                'wireModel' => 'newPasswordConfirmation',
                'label' => __('website/profile.confirm_new_password'),
                // 'has_label' => false,
            ])

            <div>
                <button type="submit" class="btn font-semibold btn-sm rounded-md btn-primary">
                    {{ __('website/profile.update_password') }}
                </button>
            </div>
        </form>
    </section>
</div>
