<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Social accounts') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Link or unlink your Google account for signing in.') }}
        </p>
    </header>

    <div class="mt-4 space-y-4">
        @if ($user->oauth_provider === 'google')
            <p class="text-sm text-gray-600">
                {{ __('Connected: Google') }}
                @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="" class="inline-block ml-2 w-8 h-8 rounded-full" />
                @endif
            </p>
            <form method="POST" action="{{ route('profile.social.unlink') }}">
                @csrf
                <x-secondary-button type="submit">{{ __('Unlink Google') }}</x-secondary-button>
            </form>
        @else
            <a href="{{ route('auth.social.redirect', ['provider' => 'google']) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50">
                {{ __('Link Google account') }}
            </a>
        @endif
    </div>
</section>
