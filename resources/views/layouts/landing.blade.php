<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ url('/') }}" class="shrink-0">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>
                        <nav class="flex items-center gap-4 sm:gap-6">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                    {{ __('Dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                    {{ __('Log in') }}
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                        {{ __('Register') }}
                                    </a>
                                @endif
                            @endauth
                            <a href="{{ route('pricing') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                {{ __('Pricing') }}
                            </a>
                        </nav>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @yield('content')
            </main>

            <footer class="bg-white border-t border-gray-200 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-4 text-sm text-gray-600">
                        <a href="{{ url('/') }}" class="hover:text-gray-900">{{ __('Home') }}</a>
                        <a href="{{ route('pricing') }}" class="hover:text-gray-900">{{ __('Pricing') }}</a>
                        @guest
                            <a href="{{ route('login') }}" class="hover:text-gray-900">{{ __('Log in') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="hover:text-gray-900">{{ __('Register') }}</a>
                            @endif
                        @endguest
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-600">
                        <p>A product of Artixcore</p>
                        <p class="mt-1">&copy; {{ date('Y') }} Artixcore. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
