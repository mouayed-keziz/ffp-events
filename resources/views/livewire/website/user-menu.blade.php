<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public array $notifications = [['message' => 'Notification 1', 'time' => '1 hour ago'], ['message' => 'Notification 2', 'time' => '2 hours ago']];

    public function logout(): void
    {
        Auth::guard('web')->logout();
        Auth::guard('visitor')->logout();
        Auth::guard('exhibitor')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('events');
    }
}; ?>

<div class="inline-flex items-center justify-end gap-2 md:gap-4">
    @if (!Auth::guard('web')->check())
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-link btn-sm">
                <div class="indicator">
                    @include('website.svg.notifications')
                    <span
                        class="absolute -top-3 -right-3 inline-flex items-center justify-center w-6 h-6 text-xs text-white bg-red-500 rounded-full">2</span>
                </div>
            </label>
            <div tabindex="0" class="mt-3 z-[1] card compact dropdown-content w-96 bg-base-100 shadow">
                <div class="card-body">
                    @foreach ($notifications as $notification)
                        <div class="alert">
                            <div class="flex-1">
                                <label>{{ $notification['message'] }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


    <div class="dropdown dropdown-end">
        <label tabindex="0" class="pt-1 btn btn-link btn-sm btn-circle">
            @include('website.svg.profile')
        </label>
        <ul tabindex="0" class="z-[1] p-1 shadow menu menu-sm dropdown-content bg-white rounded-box w-52">
            <div class="space-y-1">
                <a href="#" class="block px-3 py-1.5 rounded font-semibold hover:bg-gray-300/40">
                    {{ __('website/navbar.my_profile') }}
                </a>
                <a href="#" class="block px-3 py-1.5 rounded font-semibold hover:bg-gray-300/40">
                    {{ __('website/navbar.my_registrations') }}
                </a>
                <a wire:click="logout"
                    class="cursor-pointer px-3 py-1.5 rounded font-normal text-sm text-red-500 bg-primary/10 hover:bg-red-100 flex items-center gap-2">
                    @include('website.svg.logout')
                    <span>{{ __('website/navbar.logout') }}</span>
                </a>
            </div>
        </ul>
    </div>

    @if (Auth::guard('web')->check())
        <a href="/admin" class="btn btn-sm btn-primary mr-2 hidden md:flex">Admin</a>
    @endif

</div>
