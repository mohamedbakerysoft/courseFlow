<x-app-layout>
    <x-slot name="header">
        <div class="space-y-3">
            <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Revenue') }}</p>
            <h2 class="cf-dark-title text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                {{ __('Manual Payment Requests') }}
            </h2>
            <p class="cf-dark-copy max-w-2xl text-sm leading-7">
                {{ __('Review submitted transfer references and screenshots, then approve or reject each request.') }}
            </p>
        </div>
    </x-slot>

    <div class="cf-admin-shell">
        @include('dashboard.finance._subnav')

        <div class="cf-table-shell">
            <div class="cf-table-shell-header">
                <div>
                    <h3 class="cf-table-shell-title">{{ __('Manual Payment Requests') }}</h3>
                    <p class="cf-table-shell-copy">{{ __('Only approved manual payments grant access to the exact paid course.') }}</p>
                </div>
            </div>

            @if ($manual_payment_requests->count())
                <div class="overflow-x-auto">
                    <table class="cf-table">
                        <thead>
                            <tr>
                                <th>{{ __('Student') }}</th>
                                <th>{{ __('Course') }}</th>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Proof') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Review') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[rgba(15,23,42,0.08)] bg-white">
                            @foreach ($manual_payment_requests as $payment)
                                <tr>
                                    <td>
                                        <div>
                                            <p class="font-semibold text-[var(--color-text-primary)]">{{ $payment->user?->name ?? '-' }}</p>
                                            <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $payment->user?->email ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-semibold text-[var(--color-text-primary)]">{{ $payment->course?->title ?? '-' }}</p>
                                            <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ number_format((float) $payment->amount, 2) }} {{ $payment->currency }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-xs whitespace-pre-line text-sm text-[var(--color-text-muted)]">
                                            {{ $payment->payment_reference ?: __('Not submitted yet') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($payment->proof_url)
                                            <a href="{{ $payment->proof_url }}" target="_blank" rel="noopener">
                                                <img src="{{ $payment->proof_url }}" alt="{{ __('Payment proof') }}" class="h-20 w-20 rounded-xl object-cover ring-1 ring-[rgba(15,23,42,0.08)]">
                                            </a>
                                        @else
                                            <span class="text-sm text-[var(--color-text-muted)]">{{ __('No proof yet') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($payment->status === \App\Models\Payment::STATUS_PAID)
                                            <span class="cf-badge">{{ __('Approved') }}</span>
                                        @elseif ($payment->status === \App\Models\Payment::STATUS_FAILED)
                                            <span class="cf-badge-muted !border-[var(--color-error)]/20 !bg-[var(--color-error)]/10 !text-[var(--color-error)]">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="cf-badge-muted">{{ __('Pending') }}</span>
                                        @endif

                                        @if ($payment->submitted_at)
                                            <p class="mt-2 text-xs text-[var(--color-text-muted)]">{{ __('Submitted') }}: {{ $payment->submitted_at->format('Y-m-d H:i') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="space-y-3">
                                            @if ($payment->status === \App\Models\Payment::STATUS_PENDING)
                                                @if ($payment->is_manual_submission_complete)
                                                    <form action="{{ route('dashboard.payments.approve', $payment) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="cf-button-primary !px-4 !py-2">
                                                            {{ __('Approve') }}
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('dashboard.payments.reject', $payment) }}" method="POST" class="space-y-2">
                                                        @csrf
                                                        <textarea name="review_notes" rows="3" class="w-full rounded-xl border border-[var(--color-secondary)]/20 px-3 py-2 text-sm" placeholder="{{ __('Reason for rejection') }}"></textarea>
                                                        <button type="submit" class="inline-flex items-center rounded-md border border-[var(--color-error)]/20 px-4 py-2 text-sm font-medium text-[var(--color-error)]">
                                                            {{ __('Reject') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('Waiting for the student to submit the payment reference and screenshot.') }}</p>
                                                @endif
                                            @else
                                                @if ($payment->approver)
                                                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('Reviewed by') }} {{ $payment->approver->name }}</p>
                                                @endif
                                                @if ($payment->review_notes)
                                                    <div class="max-w-xs whitespace-pre-line text-sm text-[var(--color-text-muted)]">
                                                        {{ $payment->review_notes }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($manual_payment_requests->hasPages())
                    <div class="mt-4">
                        {{ $manual_payment_requests->links() }}
                    </div>
                @endif
            @else
                <div class="cf-table-empty">
                    <p class="cf-table-empty-title">{{ __('No manual payment requests yet') }}</p>
                    <p class="cf-table-empty-copy">{{ __('Submitted bank transfer requests will appear here for review.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
