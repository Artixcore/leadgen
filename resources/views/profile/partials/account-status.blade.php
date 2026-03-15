<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Account status') }}
        </h2>
    </header>

    <div class="mt-4 space-y-2 text-sm text-gray-600">
        <p>
            <span class="font-medium">{{ __('Status:') }}</span>
            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                @if($user->status->value === 'active') bg-green-100 text-green-800
                @elseif($user->status->value === 'suspended') bg-red-100 text-red-800
                @else bg-amber-100 text-amber-800
                @endif">
                {{ ucfirst($user->status->value) }}
            </span>
        </p>
        <p>
            <span class="font-medium">{{ __('Member since:') }}</span>
            {{ $user->created_at->format(config('app.date_format', 'F j, Y')) }}
        </p>
        <p>
            <span class="font-medium">{{ __('Email verified:') }}</span>
            {{ $user->hasVerifiedEmail() ? __('Yes') : __('No') }}
        </p>
    </div>
</section>
