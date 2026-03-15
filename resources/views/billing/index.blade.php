<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('checkout') === 'success')
                <div class="rounded-md bg-green-50 p-4 text-green-800">
                    {{ __('Your subscription has been updated successfully.') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Current Plan') }}</h3>
                    <p class="mt-1 text-gray-600">{{ $plan->name }}</p>
                    @if ($subscription)
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Status') }}: {{ $subscription->stripe_status }}
                            @if ($subscription->onTrial())
                                · {{ __('Trial ends') }}: {{ $subscription->trial_ends_at?->format('M j, Y') }}
                            @elseif ($subscription->ends_at)
                                · {{ __('Ends at') }}: {{ $subscription->ends_at->format('M j, Y') }}
                            @else
                                @php $periodEnd = $subscription->currentPeriodEnd(); @endphp
                                · {{ __('Next billing') }}: {{ $periodEnd?->format('M j, Y') ?? __('N/A') }}
                            @endif
                        </p>
                    @endif
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('billing.plans') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ $plan->isFree() ? __('Upgrade Plan') : __('Change Plan') }}
                        </a>
                        @if (!$plan->isFree() && $subscription)
                            <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Manage Billing') }}
                            </a>
                        @endif
                        <a href="{{ route('billing.invoices') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Billing History') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
