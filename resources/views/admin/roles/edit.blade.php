<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit role') }}: {{ ucfirst($role->name) }}
            </h2>
            <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Back to roles') }}</a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <p class="text-sm text-gray-600">{{ __('Select permissions for this role.') }}</p>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                        @foreach ($permissions as $permission)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        {{ __('Update role') }}
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
