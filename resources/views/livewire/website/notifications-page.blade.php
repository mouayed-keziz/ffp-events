<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\App;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $unreadCount = 0;
    public $perPage = 10;

    public function mount(): void
    {
        $this->loadUnreadCount();
    }

    public function loadUnreadCount(): void
    {
        $user = null;

        // Check which guard the user is authenticated with
        if (Auth::guard('exhibitor')->check()) {
            $user = Auth::guard('exhibitor')->user();
        } elseif (Auth::guard('visitor')->check()) {
            $user = Auth::guard('visitor')->user();
        }

        if ($user) {
            // Count unread notifications
            $this->unreadCount = $user->unreadNotifications()->count();
        } else {
            $this->unreadCount = 0;
        }
    }

    public function getNotificationsProperty()
    {
        $user = null;

        // Check which guard the user is authenticated with
        if (Auth::guard('exhibitor')->check()) {
            $user = Auth::guard('exhibitor')->user();
        } elseif (Auth::guard('visitor')->check()) {
            $user = Auth::guard('visitor')->user();
        }

        if (!$user) {
            return collect([]);
        }

        // Get notifications with pagination
        $notificationsCollection = $user->notifications()->latest()->paginate($this->perPage);

        // Format notifications for display
        return $notificationsCollection->through(function ($notification) {
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

            // Get event image if available
            $eventImage = null;
            if (isset($data['event_id'])) {
                $event = \App\Models\EventAnnouncement::find($data['event_id']);
                if ($event) {
                    $eventImage = $event->image;
                }
            }

            return [
                'id' => $notification->id,
                'message' => $message,
                'time' => $notification->created_at->diffForHumans(),
                'read' => !is_null($notification->read_at),
                'event_id' => $data['event_id'] ?? null,
                'event_title' => $eventTitle,
                'event_image' => $eventImage,
                'type' => $data['type'] ?? 'general',
            ];
        });
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
                $this->loadUnreadCount();
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
            $this->loadUnreadCount();
        }
    }

    public function viewEventAndMarkAsRead($notificationId, $eventId): void
    {
        $this->markAsRead($notificationId);

        // Get the event to use its slug
        $event = \App\Models\EventAnnouncement::find($eventId);
        if ($event) {
            $this->redirect(route('event_details', ['slug' => $event->slug]));
        }
    }
}; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">{{ __('website/notifications.title') }}</h1>
                    @if ($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="btn btn-sm btn-outline">
                            {{ __('website/notifications.mark_all_read') }}
                        </button>
                    @endif
                </div>
            </div>

            @if (count($this->notifications) > 0)
                <div class="divide-y divide-gray-200">
                    @foreach ($this->notifications as $notification)
                        <div
                            class="p-6 {{ $notification['read'] ? 'bg-white' : 'bg-blue-50' }} transition-colors duration-200 hover:bg-gray-50">
                            <div class="flex gap-4">
                                @if ($notification['event_image'])
                                    <div class="flex-shrink-0">
                                        <img src="{{ $notification['event_image'] }}"
                                            alt="{{ $notification['event_title'] }}"
                                            class="w-20 h-20 object-cover rounded-md">
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            @if ($notification['event_title'])
                                                <h3 class="font-semibold text-lg">{{ $notification['event_title'] }}
                                                </h3>
                                            @endif
                                            <p class="text-gray-700 mt-1">{{ $notification['message'] }}</p>
                                            <div class="text-sm text-gray-500 mt-2">{{ $notification['time'] }}</div>
                                        </div>

                                        @if (!$notification['read'])
                                            <button wire:click="markAsRead('{{ $notification['id'] }}')"
                                                class="btn btn-sm btn-ghost"
                                                title="{{ __('website/notifications.mark_as_read') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    @if ($notification['event_id'])
                                        <div class="mt-4">
                                            <button
                                                wire:click="viewEventAndMarkAsRead('{{ $notification['id'] }}', {{ $notification['event_id'] }})"
                                                class="btn btn-sm btn-primary">
                                                {{ __('website/notifications.view_event') }}
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-6">
                    {{ $this->notifications->links() }}
                </div>
            @else
                <div class="py-12 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-16 h-16 mx-auto text-gray-400 mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <p class="text-xl">{{ __('website/notifications.no_notifications') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
