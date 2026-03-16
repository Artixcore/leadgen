<nav class="navbar navbar-expand navbar-theme sticky-top">
    <a class="sidebar-toggle d-flex me-2" href="#" aria-label="{{ __('Toggle sidebar') }}">
        <i class="hamburger align-self-center"></i>
    </a>
    @if(config('app.logo_url'))
        <a class="d-flex align-items-center me-2" href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }}">
            <img src="{{ config('app.logo_url') }}" alt="" style="height: 32px; width: auto;">
        </a>
    @endif

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.notifications.index') }}" title="{{ __('Notifications') }}">
                    <svg class="align-middle" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-bell"/></svg>
                </a>
            </li>
            <li class="nav-item dropdown ms-lg-2">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg class="align-middle" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-cog"/></svg>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <svg class="align-middle me-1" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-user"/></svg> {{ __('Profile') }}
                    </a>
                    @can('manage-settings')
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <svg class="align-middle me-1" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-cog"/></svg> {{ __('Settings') }}
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <svg class="align-middle me-1" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-sign-out"/></svg> {{ __('Sign out') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
