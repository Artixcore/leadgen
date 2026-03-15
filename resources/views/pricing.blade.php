@extends('layouts.landing')

@section('title', __('Pricing') . ' - ' . config('app.name'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h1 class="text-3xl font-semibold text-gray-900 sm:text-4xl">
                {{ __('Pricing') }}
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                {{ __('Choose the plan that fits your team. Upgrade or change anytime.') }}
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($plans as $plan)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col">
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                        @if ($plan->description)
                            <p class="mt-2 text-sm text-gray-600">{{ $plan->description }}</p>
                        @endif
                        <ul class="mt-4 space-y-2 text-sm text-gray-600 flex-1">
                            <li>{{ $plan->leads_per_month === null ? __('Unlimited') : number_format($plan->leads_per_month) }} {{ __('leads/month') }}</li>
                            <li>{{ number_format($plan->exports_per_month) }} {{ __('exports/month') }}</li>
                            <li>{{ number_format($plan->saved_lists_count) }} {{ __('saved lists') }}</li>
                            <li>{{ $plan->api_access ? __('Yes') : __('No') }} {{ __('API access') }}</li>
                            <li>{{ $plan->hasTeamMembers() ? number_format($plan->team_members_limit) : __('No') }} {{ __('team members') }}</li>
                            <li>{{ $plan->advanced_filters ? __('Yes') : __('No') }} {{ __('advanced filters') }}</li>
                        </ul>
                        <div class="mt-6 space-y-2">
                            @if ($plan->isFree())
                                <p class="text-gray-700 font-medium">{{ __('Free forever') }}</p>
                                @guest
                                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                                        {{ __('Get started') }}
                                    </a>
                                @else
                                    <a href="{{ route('billing.plans') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 transition">
                                        {{ __('Choose plan') }}
                                    </a>
                                @endguest
                            @else
                                @guest
                                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md font-medium text-sm hover:bg-gray-700 transition">
                                        {{ __('Get started') }}
                                    </a>
                                @else
                                    <a href="{{ route('billing.plans') }}" class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md font-medium text-sm hover:bg-gray-700 transition">
                                        {{ __('Choose plan') }}
                                    </a>
                                @endguest
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($plans->isEmpty())
            <div class="text-center py-12 text-gray-600">
                {{ __('No plans are available at the moment. Please check back later.') }}
            </div>
        @endif
    </div>
@endsection
