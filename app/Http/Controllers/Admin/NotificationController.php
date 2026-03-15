<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = DB::table('notifications')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return view('admin.notifications.index', ['notifications' => $notifications]);
    }
}
