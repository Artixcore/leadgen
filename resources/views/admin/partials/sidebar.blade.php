<nav id="sidebar" class="sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <svg>
            <use xlink:href="#ion-ios-pulse-strong"></use>
        </svg>
        {{ config('app.name') }}
    </a>
    <div class="sidebar-content">
        <div class="sidebar-user">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&size=64&background=random" class="img-fluid rounded-circle mb-2" alt="{{ Auth::user()->name }}">
            <div class="fw-bold">{{ Auth::user()->name }}</div>
            <small>{{ Auth::user()->email }}</small>
        </div>

        <ul class="sidebar-nav">
            <li class="sidebar-header">{{ __('Main') }}</li>

            <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-dashboard"/></svg>
                    <span class="align-middle">{{ __('Dashboard') }}</span>
                </a>
            </li>

            @can('manage-users')
            <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-users"/></svg>
                    <span class="align-middle">{{ __('Users') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-leads')
            <li class="sidebar-item {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.leads.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-leads"/></svg>
                    <span class="align-middle">{{ __('Leads') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-lead-sources')
            @php $leadSourcesActive = request()->routeIs('admin.lead-sources.*') || request()->routeIs('admin.import-runs.*') || request()->routeIs('admin.lead-collectors.*'); @endphp
            <li class="sidebar-item {{ $leadSourcesActive ? 'active' : '' }}">
                <a data-bs-target="#lead-sources-menu" data-bs-toggle="collapse" class="sidebar-link {{ $leadSourcesActive ? '' : 'collapsed' }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-database"/></svg>
                    <span class="align-middle">{{ __('Lead Sources') }}</span>
                </a>
                <ul id="lead-sources-menu" class="sidebar-dropdown list-unstyled collapse {{ $leadSourcesActive ? 'show' : '' }}" data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.lead-sources.*') ? 'active' : '' }}" href="{{ route('admin.lead-sources.index') }}">{{ __('Sources') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.import-runs.*') ? 'active' : '' }}" href="{{ route('admin.import-runs.index') }}">{{ __('Import Logs') }}</a></li>
                    @can('manage-lead-collectors')
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.lead-collectors.*') ? 'active' : '' }}" href="{{ route('admin.lead-collectors.index') }}">{{ __('Lead Collectors') }}</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('manage-subscription-plans')
            <li class="sidebar-item {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.plans.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-box"/></svg>
                    <span class="align-middle">{{ __('Subscription Plans') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-payments')
            <li class="sidebar-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.subscriptions.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-credit-card"/></svg>
                    <span class="align-middle">{{ __('Subscriptions') }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.payments.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-receipt"/></svg>
                    <span class="align-middle">{{ __('Payments') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-lead-search')
            @php $leadSearchActive = request()->routeIs('admin.lead-search.*'); @endphp
            <li class="sidebar-item {{ $leadSearchActive ? 'active' : '' }}">
                <a data-bs-target="#lead-search-menu" data-bs-toggle="collapse" class="sidebar-link {{ $leadSearchActive ? '' : 'collapsed' }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-search"/></svg>
                    <span class="align-middle">{{ __('Lead Search') }}</span>
                </a>
                <ul id="lead-search-menu" class="sidebar-dropdown list-unstyled collapse {{ $leadSearchActive ? 'show' : '' }}" data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.lead-search.analytics') ? 'active' : '' }}" href="{{ route('admin.lead-search.analytics') }}">{{ __('Analytics') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.lead-search.providers*') ? 'active' : '' }}" href="{{ route('admin.lead-search.providers') }}">{{ __('Providers') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.lead-search.query-logs*') ? 'active' : '' }}" href="{{ route('admin.lead-search.query-logs') }}">{{ __('Query logs') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.lead-search.saved-searches') ? 'active' : '' }}" href="{{ route('admin.lead-search.saved-searches') }}">{{ __('Saved searches') }}</a></li>
                </ul>
            </li>
            @endcan

            @can('view-reports')
            @php $reportsActive = request()->routeIs('admin.reports.*'); @endphp
            <li class="sidebar-item {{ $reportsActive ? 'active' : '' }}">
                <a data-bs-target="#reports-menu" data-bs-toggle="collapse" class="sidebar-link {{ $reportsActive ? '' : 'collapsed' }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-chart"/></svg>
                    <span class="align-middle">{{ __('Reports') }}</span>
                </a>
                <ul id="reports-menu" class="sidebar-dropdown list-unstyled collapse {{ $reportsActive ? 'show' : '' }}" data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">{{ __('Overview') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.leads-over-time') }}">{{ __('Leads Over Time') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.source-performance') }}">{{ __('Source Performance') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.most-active-users') }}">{{ __('Most Active Users') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.revenue-by-month') }}">{{ __('Revenue by Month') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.plan-distribution') }}">{{ __('Plan Distribution') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.export-usage-trends') }}">{{ __('Export Usage') }}</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('admin.reports.lead-verification-trends') }}">{{ __('Lead Verification') }}</a></li>
                </ul>
            </li>
            @endcan

            <li class="sidebar-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.notifications.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-bell"/></svg>
                    <span class="align-middle">{{ __('Notifications') }}</span>
                </a>
            </li>

            @can('manage-settings')
            <li class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.settings.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-cog"/></svg>
                    <span class="align-middle">{{ __('Settings') }}</span>
                </a>
            </li>
            @endcan

            @can('view-activity-log')
            <li class="sidebar-item {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.activity-log.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-history"/></svg>
                    <span class="align-middle">{{ __('Activity Log') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-users')
            <li class="sidebar-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.roles.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-shield"/></svg>
                    <span class="align-middle">{{ __('Roles') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-categories')
            <li class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.categories.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-tags"/></svg>
                    <span class="align-middle">{{ __('Categories') }}</span>
                </a>
            </li>
            @endcan

            @can('manage-countries')
            <li class="sidebar-item {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.countries.index') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-globe"/></svg>
                    <span class="align-middle">{{ __('Countries') }}</span>
                </a>
            </li>
            @endcan

            <li class="sidebar-header">{{ __('Account') }}</li>
            <li class="sidebar-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('profile.edit') }}">
                    <svg class="align-middle me-2" width="1em" height="1em" fill="currentColor" aria-hidden="true"><use xlink:href="#icon-user"/></svg>
                    <span class="align-middle">{{ __('Profile') }}</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
