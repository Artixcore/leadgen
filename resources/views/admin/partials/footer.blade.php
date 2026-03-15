<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-8 text-start">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a class="text-muted" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-4 text-end">
                <p class="mb-0">
                    &copy; {{ date('Y') }} - <a class="text-muted" href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
                </p>
            </div>
        </div>
    </div>
</footer>
