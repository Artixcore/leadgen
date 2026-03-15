<nav class="navbar navbar-expand navbar-theme">
    <a class="sidebar-toggle d-flex me-2" href="#" aria-label="{{ __('Toggle sidebar') }}">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.notifications.index') }}" title="{{ __('Notifications') }}">
                    <i class="align-middle fas fa-bell"></i>
                </a>
            </li>
            <li class="nav-item dropdown ms-lg-2">
                <a class="nav-link dropdown-toggle position-relative" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="align-middle fas fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="align-middle me-1 fas fa-fw fa-user"></i> {{ __('Profile') }}
                    </a>
                    @can('manage-settings')
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <i class="align-middle me-1 fas fa-fw fa-cogs"></i> {{ __('Settings') }}
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="align-middle me-1 fas fa-fw fa-arrow-alt-circle-right"></i> {{ __('Sign out') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
