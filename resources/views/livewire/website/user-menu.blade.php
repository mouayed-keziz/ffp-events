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
                    <span class="badge badge-xs badge-error indicator-item">2</span>
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
        <ul tabindex="0" class="z-[1] p-4 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52 gap-2">
            <div class="px-3 py-2 border-b mb-2 flex items-center gap-2">
                @if (checkUser() && currentUser()->image)
                    <div class="w-10 rounded-full">
                        <img src="{{ currentUser()->image }}" alt="User Profile Photo" />
                    </div>
                @else
                    @php
                        $name = currentUser()->name;
                        $parts = explode(' ', $name);
                        $initials =
                            count($parts) > 1
                                ? implode('', array_map(fn($n) => strtoupper($n[0]), $parts))
                                : strtoupper($parts[0][0]);
                    @endphp
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content w-10 rounded-full">
                            <span class="text-xl">{{ $initials }}</span>
                        </div>
                    </div>
                @endif
                <div>
                    <div class="font-bold">{{ currentUser()->name }}</div>
                    <div class="text-xs">{{ currentUser()?->email }}</div>
                </div>
            </div>
            <li>
                <a href="#" class="justify-between">
                    {{ __('website/navbar.profile') }}
                </a>
            </li>
            <li>
                <a wire:click="logout" class="justify-between">
                    {{ __('website/navbar.logout') }}
                </a>
            </li>
        </ul>
    </div>

    @if (Auth::guard('web')->check())
        <a href="/admin" class="btn btn-sm btn-primary mr-2 hidden md:flex">Admin</a>
    @endif

</div>
