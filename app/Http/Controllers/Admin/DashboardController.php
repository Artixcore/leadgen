<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardStatsService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function __invoke(AdminDashboardStatsService $stats): View
    {
        return view('admin.dashboard', [
            'stats' => $stats->getStats(),
        ]);
    }
}
