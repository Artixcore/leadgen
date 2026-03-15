<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <p class="text-sm text-gray-600">
            {{ __('Your recent notifications.') }}
        </p>

        <x-card>
            @if ($notifications->isEmpty())
                <p class="text-gray-600">{{ __('You have no notifications.') }}</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($notifications as $notification)
                        <li class="py-4 {{ $notification->read_at ? '' : 'bg-gray-50 -mx-4 sm:-mx-6 px-4 sm:px-6' }}">
                            <div class="flex gap-3 sm:gap-4">
                                <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-900">
                                        @if (is_array($notification->data) && isset($notification->data['message']))
                                            {{ $notification->data['message'] }}
                                        @else
                                            {{ __('Notification') }}
                                        @endif
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                        @if ($notification->read_at)
                                            <span class="text-gray-400"> · {{ __('Read') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
