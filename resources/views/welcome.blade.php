@extends('layouts.landing')

@section('title', config('app.name') . ' - ' . __('Lead Generation Platform'))

@section('content')
    <div class="relative">
        {{-- Hero --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                    {{ __('Find and grow your best leads') }}
                </h1>
                <p class="mt-6 text-lg text-gray-600">
                    {{ __('Search verified leads, build lists, and export data that fits your pipeline. Simple plans for teams of any size.') }}
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 transition">
                            {{ __('Go to Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 transition">
                            {{ __('Get started') }}
                        </a>
                        <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                            {{ __('View pricing') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Features --}}
        <div class="bg-white py-16 sm:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-center text-2xl font-semibold text-gray-900 sm:text-3xl">
                    {{ __('Everything you need to scale outreach') }}
                </h2>
                <p class="mt-4 text-center max-w-2xl mx-auto text-gray-600">
                    {{ __('Search, filter, and export leads with clear limits and transparent pricing.') }}
                </p>
                <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Lead search') }}</h3>
                        <p class="mt-2 text-sm text-gray-600">{{ __('Filter by industry, location, company size, and more. Access verified contact data.') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Saved lists') }}</h3>
                        <p class="mt-2 text-sm text-gray-600">{{ __('Organize leads into lists, share with your team, and export when you need to.') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Export & billing') }}</h3>
                        <p class="mt-2 text-sm text-gray-600">{{ __('Export to CSV on the plan that fits you. Clear limits and billing you control.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
            <div class="bg-gray-800 rounded-2xl px-6 py-12 sm:px-12 sm:py-16 text-center">
                <h2 class="text-2xl font-semibold text-white sm:text-3xl">
                    {{ __('Ready to get started?') }}
                </h2>
                <p class="mt-4 text-lg text-gray-300">
                    {{ __('Choose a plan and start finding leads in minutes.') }}
                </p>
                @guest
                    <div class="mt-8">
                        <a href="{{ route('pricing') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-gray-900 bg-white hover:bg-gray-100 transition">
                            {{ __('View pricing') }}
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </div>
@endsection
