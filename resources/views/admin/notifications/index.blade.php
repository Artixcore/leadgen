<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Type') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Notifiable') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Read at') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($notifications as $notification)
                                    @php
                                        $data = json_decode($notification->data ?? '{}', true);
                                        $type = $notification->type ? class_basename($notification->type) : '—';
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $type }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $notification->notifiable_type }} #{{ $notification->notifiable_id }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $notification->read_at ? \Carbon\Carbon::parse($notification->read_at)->format('M j, Y H:i') : '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->format('M j, Y H:i') : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No notifications.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
