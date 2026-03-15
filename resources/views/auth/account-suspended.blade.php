<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Your account has been suspended. Please contact support if you believe this is an error.') }}
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-primary-button type="submit">
            {{ __('Log out') }}
        </x-primary-button>
    </form>
</x-guest-layout>
