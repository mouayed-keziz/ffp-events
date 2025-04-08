<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\App;

new class extends Component {
    public $notifications = [];
    public $unreadCount = 0;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = null;

        // Check which guard the user is authenticated with
        if (Auth::guard('exhibitor')->check()) {
            $user = Auth::guard('exhibitor')->user();
        } elseif (Auth::guard('visitor')->check()) {
            $user = Auth::guard('visitor')->user();
        }

        if ($user) {
            // Get notifications with pagination
            $notificationsCollection = $user->notifications()->latest()->take(5)->get();

            // Count unread notifications
            $this->unreadCount = $user->unreadNotifications()->count();

            // Format notifications for display
            $this->notifications = $notificationsCollection
                ->map(function ($notification) {
                    $locale = App::getLocale();
                    $data = $notification->data;

                    // Get translated message based on current locale
                    $message = isset($data['message'][$locale]) ? $data['message'][$locale] : $data['message'] ?? 'No message';

                    // Get translated event title if available
                    $eventTitle = null;
                    if (isset($data['event_title'][$locale])) {
                        $eventTitle = $data['event_title'][$locale];
                    } elseif (isset($data['event_title']) && !is_array($data['event_title'])) {
                        $eventTitle = $data['event_title'];
                    }

                    return [
                        'id' => $notification->id,
                        'message' => $message,
                        'time' => $notification->created_at->diffForHumans(),
                        'read' => !is_null($notification->read_at),
                        'event_id' => $data['event_id'] ?? null,
                        'event_title' => $eventTitle,
                        'type' => $data['type'] ?? 'general',
                    ];
                })
                ->toArray();
        } else {
            $this->notifications = [];
            $this->unreadCount = 0;
        }
    }

    public function markAsRead($notificationId): void
    {
        $user = null;

        if (Auth::guard('exhibitor')->check()) {
            $user = Auth::guard('exhibitor')->user();
        } elseif (Auth::guard('visitor')->check()) {
            $user = Auth::guard('visitor')->user();
        }

        if ($user) {
            $notification = $user->notifications()->where('id', $notificationId)->first();
            if ($notification) {
                $notification->markAsRead();
                $this->loadNotifications();
            }
        }
    }

    public function markAllAsRead(): void
    {
        $user = null;

        if (Auth::guard('exhibitor')->check()) {
            $user = Auth::guard('exhibitor')->user();
        } elseif (Auth::guard('visitor')->check()) {
            $user = Auth::guard('visitor')->user();
        }

        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->loadNotifications();
        }
    }

    public function viewEventAndMarkAsRead($notificationId, $eventId): void
    {
        $this->markAsRead($notificationId);

        // Redirect to the event page
        $this->redirect(route('event_details', ['id' => $eventId]));
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();
        Auth::guard('visitor')->logout();
        Auth::guard('exhibitor')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('login');
    }
}; ?>

<div class="inline-flex items-center justify-end gap-2 md:gap-4">
    @if (!Auth::guard('web')->check())
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-link btn-sm">
                <div class="indicator">
                    @include('website.svg.notifications')
                    @if ($unreadCount > 0)
                        <span
                            class="absolute -top-3 -right-3 inline-flex items-center justify-center w-6 h-6 text-xs text-white bg-red-500 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </div>
            </label>
            <div tabindex="0" class="mt-3 z-[9999] card compact dropdown-content w-96 bg-base-100 shadow">
                <div class="card-body p-2">
                    <h3 class="font-bold text-lg px-2 py-2 border-b">{{ __('website/notifications.title') }}</h3>

                    @if (count($notifications) > 0)
                        <div class="max-h-80 overflow-y-auto">
                            @foreach ($notifications as $notification)
                                <div
                                    class="p-3 mb-2 rounded-lg {{ $notification['read'] ? 'bg-gray-50' : 'bg-blue-50' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm">
                                                @if ($notification['event_title'])
                                                    <strong>{{ $notification['event_title'] }}</strong>:
                                                @endif
                                                {{ $notification['message'] }}
                                            </p>
                                            <div class="text-xs text-gray-500 mt-1">{{ $notification['time'] }}</div>
                                        </div>
                                        @if (!$notification['read'])
                                            <button wire:click="markAsRead('{{ $notification['id'] }}')"
                                                class="btn btn-xs btn-ghost"
                                                title="{{ __('website/notifications.mark_as_read') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if ($notification['event_id'])
                                        <div class="mt-2">
                                            <button
                                                wire:click="viewEventAndMarkAsRead('{{ $notification['id'] }}', {{ $notification['event_id'] }})"
                                                class="btn btn-xs btn-primary w-full">
                                                {{ __('website/notifications.view_event') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end px-2 mb-1">
                            <a href="{{ route('notifications') }}"
                                class="text-sm text-primary hover:underline font-medium flex items-center gap-1">
                                {{ __('website/notifications.see_all') }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>

                        @if ($unreadCount > 0)
                            <div class="border-t pt-2 px-2">
                                <button wire:click="markAllAsRead" class="btn btn-sm btn-outline w-full">
                                    {{ __('website/notifications.mark_all_read') }}
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="py-6 text-center text-gray-500">
                            {{ __('website/notifications.no_notifications') }}
                        </div>
                    @endif
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
