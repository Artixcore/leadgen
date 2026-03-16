<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name') }} Admin">

    <title>@yield('title', __('Admin')) - {{ config('app.name') }}</title>

    @if(config('app.logo_url'))
    <link rel="icon" type="image/png" href="{{ config('app.logo_url') }}">
    <link rel="apple-touch-icon" href="{{ config('app.logo_url') }}">
    @endif

    <link href="{{ asset('vendor/admin-template/css/modern.css') }}" rel="stylesheet">
    @stack('styles')
</head>







<body>

    <div class="wrapper">
        @include('admin.partials.sidebar')

        <div class="main">
            @include('admin.partials.navbar')

            <main class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
                <path d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z"></path>
            </symbol>
            <symbol viewBox="0 0 24 24" id="icon-dashboard"><path fill="currentColor" d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-users"><path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-leads"><path fill="currentColor" d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12zM6 10h2.5v6H6zm4.5 0H14c.55 0 1 .45 1 1v1.5c0 .55-.45 1-1 1h-1v1h1.5V15H12v-3h-1.5v4H9v-5c0-.55.45-1 1-1zm5.5 0H18v1.5h-1.5V10zm0 3H18V15h-1.5v-2z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-database"><path fill="currentColor" d="M12 3C7.58 3 4 4.79 4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7c0-2.21-3.58-4-8-4zm0 2c3.87 0 6 1.5 6 2s-2.13 2-6 2-6-1.5-6-2 2.13-2 6-2zm0 14c-3.87 0-6-1.5-6-2v-4c1.77 1.22 3.89 2 6 2s4.23-.78 6-2v4c0 .5-2.13 2-6 2zm0-8c-3.87 0-6-1.5-6-2V9c1.77 1.22 3.89 2 6 2s4.23-.78 6-2v2c0 .5-2.13 2-6 2z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-search"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-box"><path fill="currentColor" d="M20 8H4V6h16v2zm-2-6H6c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 6H6V4h12v4zM20 18H4v-2h16v2zm0-4H4v-2h16v2z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-credit-card"><path fill="currentColor" d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-receipt"><path fill="currentColor" d="M18 17H6v-2h12v2zm0-4H6v-2h12v2zm0-4H6V7h12v2zM3 22l1.5-1.5L6 22l1.5-1.5L9 22l1.5-1.5L12 22l1.5-1.5L15 22l1.5-1.5L18 22l1.5-1.5L21 22V2l-1.5 1.5L18 2l-1.5 1.5L15 2l-1.5 1.5L12 2l-1.5 1.5L9 2 7.5 3.5 6 2 4.5 3.5 3 2v20z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-chart"><path fill="currentColor" d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-bell"><path fill="currentColor" d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-cog"><path fill="currentColor" d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-history"><path fill="currentColor" d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-shield"><path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-tags"><path fill="currentColor" d="M5 5v2h2V5H5zm4 4V7h2v2H9zm-4 6v2h2v-2H5zm14-8.5V10h-2V6.5h-2v-2h4v2h2zm-6 2.5h2v2h-2v-2zm4-4h2v2h-2V7zm-4 6h2v2h-2v-2zm6.5 2v-2h-2v2h2zm-2 2v2h-2v-2h2zm-6 0v2H7v-2h2zm-2-2v-2H5v2h2z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-globe"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-user"><path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></symbol>
            <symbol viewBox="0 0 24 24" id="icon-sign-out"><path fill="currentColor" d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></symbol>
        </defs>
    </svg>

    <script src="{{ asset('vendor/admin-template/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
