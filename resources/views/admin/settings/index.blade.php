<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 max-w-lg">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="app_name" class="block text-sm font-medium text-gray-700">{{ __('App name') }}</label>
                        <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('app_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">{{ __('Contact email') }}</label>
                        <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('contact_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="maintenance_mode" class="block text-sm font-medium text-gray-700">{{ __('Maintenance mode') }}</label>
                        <select id="maintenance_mode" name="maintenance_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="0" @selected(($settings['maintenance_mode'] ?? '0') === '0')>{{ __('No') }}</option>
                            <option value="1" @selected(($settings['maintenance_mode'] ?? '0') === '1')>{{ __('Yes') }}</option>
                        </select>
                        @error('maintenance_mode')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">{{ __('Save settings') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
