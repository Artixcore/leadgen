<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Revenue this month') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($revenueCurrentMonth, 2) }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">{{ __('Revenue last month') }}</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($revenuePreviousMonth, 2) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Date') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('User') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Plan') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Amount') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Invoice') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($payments as $payment)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $payment->paid_at?->format('M j, Y H:i') ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->user?->name ?? $payment->user?->email ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->plan?->name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $payment->stripe_invoice_id ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">{{ __('No payments recorded yet. Payments are stored when Stripe sends invoice.paid webhooks.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $payments->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
