<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::query()
            ->with(['user', 'plan'])
            ->orderByDesc('paid_at')
            ->paginate(25);

        $revenueCurrentMonth = Payment::query()
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        $revenuePreviousMonth = Payment::query()
            ->whereYear('paid_at', now()->subMonth()->year)
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->sum('amount');

        return view('admin.payments.index', [
            'payments' => $payments,
            'revenueCurrentMonth' => $revenueCurrentMonth,
            'revenuePreviousMonth' => $revenuePreviousMonth,
        ]);
    }
}
