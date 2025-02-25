<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $token;
    public $user; // 'user', 'exhibitor', or 'visitor'
    public $email;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->token = request()->query('token');
        $this->user = request()->query('user');

        $record = DB::table('password_reset_tokens')->where('token', $this->token)->first();
        if (!$record) {
            return redirect()->route('login');
        }
        $this->email = $record->email;

        if ($this->user === 'user') {
            $model = \App\Models\User::class;
        } elseif ($this->user === 'exhibitor') {
            $model = \App\Models\Exhibitor::class;
        } elseif ($this->user === 'visitor') {
            $model = \App\Models\Visitor::class;
        } else {
            return redirect()->route('login');
        }
        $userRecord = $model::where('email', $this->email)->first();
        if (!$userRecord) {
            return redirect()->route('login');
        }
    }

    public function submit()
    {
        $this->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        if ($this->user === 'user') {
            $model = \App\Models\User::class;
            $guard = 'web';
        } elseif ($this->user === 'exhibitor') {
            $model = \App\Models\Exhibitor::class;
            $guard = 'exhibitor';
        } elseif ($this->user === 'visitor') {
            $model = \App\Models\Visitor::class;
            $guard = 'visitor';
        } else {
            return redirect()->route('login');
        }

        $userRecord = $model::where('email', $this->email)->first();
        if (!$userRecord) {
            return redirect()->route('login');
        }

        $userRecord->password = Hash::make($this->password);
        $userRecord->save();

        // Delete the token after successful reset.
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        auth()->guard($guard)->login($userRecord);
        return redirect()->route('events');
    }
}; ?>

<div class="container mx-auto mt-16">
    <div class="card p-6 max-w-md mx-auto shadow-lg bg-white rounded-lg">
        <h2 class="text-xl font-bold mb-4">{{ __('website/reset_password.title') }}</h2>
        <form wire:submit.prevent="submit">
            @csrf
            {{-- Password input component --}}
            @include('website.components.global.password-input', [
                'name' => 'password',
                'wireModel' => 'password',
                'placeholder' => '••••••••••••••',
                'label' => __('website/reset_password.password_label'),
            ])

            {{-- Password confirmation input component --}}
            @include('website.components.global.password-input', [
                'name' => 'password_confirmation',
                'wireModel' => 'password_confirmation',
                'placeholder' => '••••••••••••••',
                'label' => __('website/reset_password.password_confirmation_label'),
            ])

            <div class="form-control mt-4">
                <button type="submit" class="btn btn-neutral w-full rounded-lg">
                    {{ __('website/reset_password.submit_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
