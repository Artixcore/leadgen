<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subscription Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('checkout') === 'cancelled')
                <div class="mb-6 rounded-md bg-gray-50 p-4 text-gray-700">
                    {{ __('Checkout was cancelled.') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('billing.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; {{ __('Back to Billing') }}
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($plans as $plan)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 {{ $currentPlan->id === $plan->id ? 'border-gray-800' : 'border-gray-200' }}">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                            @if ($currentPlan->id === $plan->id)
                                <span class="inline-block mt-1 text-xs font-medium text-gray-600 bg-gray-100 px-2 py-0.5 rounded">{{ __('Current') }}</span>
                            @endif
                            @if ($plan->description)
                                <p class="mt-2 text-sm text-gray-600">{{ $plan->description }}</p>
                            @endif
                            <ul class="mt-4 space-y-2 text-sm text-gray-600">
                                <li>{{ $plan->leads_per_month === null ? __('Unlimited') : number_format($plan->leads_per_month) }} {{ __('leads/month') }}</li>
                                <li>{{ number_format($plan->exports_per_month) }} {{ __('exports/month') }}</li>
                                <li>{{ number_format($plan->saved_lists_count) }} {{ __('saved lists') }}</li>
                                <li>{{ $plan->api_access ? __('Yes') : __('No') }} {{ __('API access') }}</li>
                                <li>{{ $plan->hasTeamMembers() ? number_format($plan->team_members_limit) : __('No') }} {{ __('team members') }}</li>
                                <li>{{ $plan->advanced_filters ? __('Yes') : __('No') }} {{ __('advanced filters') }}</li>
                            </ul>
                            @if ($plan->isFree())
                                <p class="mt-4 text-gray-700 font-medium">{{ __('Free forever') }}</p>
                            @else
                                <div class="mt-4 flex flex-col gap-2">
                                    @if ($plan->stripe_price_id_monthly)
                                        <a href="{{ route('billing.checkout', ['plan' => $plan, 'interval' => 'monthly']) }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700 transition">
                                            {{ __('Monthly') }}
                                        </a>
                                    @endif
                                    @if ($plan->stripe_price_id_yearly)
                                        <a href="{{ route('billing.checkout', ['plan' => $plan, 'interval' => 'yearly']) }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-50 transition">
                                            {{ __('Yearly') }}
                                        </a>
                                    @endif
                                    @if (!$plan->stripe_price_id_monthly && !$plan->stripe_price_id_yearly)
                                        <p class="text-sm text-gray-500">{{ __('Configure Stripe prices for this plan.') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
