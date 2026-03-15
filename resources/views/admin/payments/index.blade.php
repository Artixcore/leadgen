@extends('admin.layouts.app')

@section('title', __('Payments'))

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Payments'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Payments') => null,
        ],
    ])

    <div class="row mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Revenue this month') }}</h5>
                    <h1 class="display-5 mt-1 mb-0">${{ number_format($revenueCurrentMonth, 2) }}</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Revenue last month') }}</h5>
                    <h1 class="display-5 mt-1 mb-0">${{ number_format($revenuePreviousMonth, 2) }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Payment history') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Plan') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Invoice') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $payment->paid_at?->format('M j, Y H:i') ?? '—' }}</td>
                                <td>{{ $payment->user?->name ?? $payment->user?->email ?? '—' }}</td>
                                <td>{{ $payment->plan?->name ?? '—' }}</td>
                                <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->stripe_invoice_id ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('No payments recorded yet. Payments are stored when Stripe sends invoice.paid webhooks.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@endsection
