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
                <div class="grid grid-cols-1 gap-4 border-b border-[rgba(15,23,42,0.08)] px-6 py-5 sm:grid-cols-3">
                    <div class="cf-stat-card space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Queue size') }}</p>
                        <p class="text-3xl font-bold text-[var(--color-text-primary)]">{{ $manual_payment_requests->total() }}</p>
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('All requests visible in the current review queue.') }}</p>
                    </div>
                    <div class="cf-stat-card space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Pending review') }}</p>
                        <p class="text-3xl font-bold text-[var(--color-text-primary)]">
                            {{ $manual_payment_requests->getCollection()->where('status', \App\Models\Payment::STATUS_PENDING)->count() }}
                        </p>
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('Requests that still need a decision from the admin.') }}</p>
                    </div>
                    <div class="cf-stat-card space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Ready now') }}</p>
                        <p class="text-3xl font-bold text-[var(--color-text-primary)]">
                            {{ $manual_payment_requests->getCollection()->filter(fn ($payment) => $payment->status === \App\Models\Payment::STATUS_PENDING && $payment->is_manual_submission_complete)->count() }}
                        </p>
                        <p class="text-sm text-[var(--color-text-muted)]">{{ __('Reference and screenshot are both available.') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="cf-table min-w-[980px]">
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
                        <tbody class="divide-y divide-[rgba(15,23,42,0.06)] bg-white dark:divide-white/[0.06] dark:bg-transparent">
                            @foreach ($manual_payment_requests as $payment)
                                <tr class="align-top">
                                    <td>
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)]/12 text-sm font-bold text-[var(--color-primary)]">
                                                {{ \Illuminate\Support\Str::of($payment->user?->name ?? 'U')->substr(0, 1)->upper() }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold leading-6 text-[var(--color-text-primary)]">{{ $payment->user?->name ?? '-' }}</p>
                                                <p class="mt-1 break-all text-sm text-[var(--color-text-muted)]">{{ $payment->user?->email ?? '-' }}</p>
                                                @if ($payment->submitted_at)
                                                    <p class="mt-3 text-xs font-medium uppercase tracking-[0.14em] text-[var(--color-text-muted)]">
                                                        {{ __('Submitted') }}: {{ $payment->submitted_at->format('Y-m-d H:i') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-[240px]">
                                            <p class="font-semibold leading-7 text-[var(--color-text-primary)]">{{ $payment->course?->title ?? '-' }}</p>
                                            <div class="mt-3 inline-flex items-center rounded-full border border-[var(--color-primary)]/18 bg-[var(--color-primary)]/10 px-3 py-1 text-xs font-semibold text-[var(--color-primary)]">
                                                {{ number_format((float) $payment->amount, 2) }} {{ $payment->currency }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-[230px] rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.03)] px-4 py-3 dark:border-white/[0.08] dark:bg-white/[0.03]">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[var(--color-text-muted)]">{{ __('Transfer reference') }}</p>
                                            <div class="mt-2 break-words whitespace-pre-line font-mono text-sm leading-6 text-[var(--color-text-primary)]">
                                                {{ $payment->payment_reference ?: __('Not submitted yet') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($payment->proof_url)
                                            <a href="{{ $payment->proof_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-3 rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.03)] px-3 py-3 transition hover:border-[var(--color-primary)]/30 hover:bg-[var(--color-primary)]/5 dark:border-white/[0.08] dark:bg-white/[0.03]">
                                                <img src="{{ $payment->proof_url }}" alt="{{ __('Payment proof') }}" class="h-16 w-16 rounded-xl object-cover ring-1 ring-black/5 dark:ring-white/10">
                                                <span class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Open proof') }}</span>
                                            </a>
                                        @else
                                            <div class="inline-flex min-h-16 min-w-[120px] items-center justify-center rounded-2xl border border-dashed border-[rgba(15,23,42,0.12)] px-4 text-center text-sm text-[var(--color-text-muted)] dark:border-white/[0.12]">
                                                {{ __('No proof yet') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="space-y-2">
                                        @if ($payment->status === \App\Models\Payment::STATUS_PAID)
                                            <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-emerald-700 dark:bg-emerald-500/18 dark:text-emerald-300">{{ __('Approved') }}</span>
                                        @elseif ($payment->status === \App\Models\Payment::STATUS_FAILED)
                                            <span class="inline-flex items-center rounded-full bg-red-500/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-red-700 dark:bg-red-500/18 dark:text-red-300">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-[var(--color-primary)]/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-[var(--color-primary)]">{{ __('Pending') }}</span>
                                        @endif

                                            <p class="max-w-[180px] text-sm leading-6 text-[var(--color-text-muted)]">
                                                {{ $payment->is_manual_submission_complete ? __('Ready for a final decision.') : __('Waiting for the student to finish submission.') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="max-w-[300px] space-y-3">
                                            @if ($payment->status === \App\Models\Payment::STATUS_PENDING)
                                                @if ($payment->is_manual_submission_complete)
                                                    <div class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.03)] p-4 dark:border-white/[0.08] dark:bg-white/[0.03]">
                                                        <p class="mb-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-[var(--color-text-muted)]">{{ __('Review actions') }}</p>
                                                        <div class="space-y-3">
                                                        <form action="{{ route('dashboard.payments.approve', $payment) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="cf-button-primary !w-full !justify-center !px-4 !py-2.5 text-xs font-bold uppercase tracking-[0.14em]">
                                                                {{ __('Approve') }}
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('dashboard.payments.reject', $payment) }}" method="POST" class="space-y-2">
                                                            @csrf
                                                            <textarea name="review_notes" rows="3" class="w-full rounded-xl border border-[rgba(15,23,42,0.08)] bg-white px-3 py-2.5 text-sm text-[var(--color-text-primary)] placeholder:text-[var(--color-text-muted)] focus:border-[var(--color-primary)] focus:outline-none focus:ring-0 dark:border-white/[0.08] dark:bg-[#111111]" placeholder="{{ __('Reason for rejection') }}"></textarea>
                                                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-red-300/30 bg-red-500/8 px-4 py-2.5 text-xs font-bold uppercase tracking-[0.14em] text-red-700 transition hover:bg-red-500/12 dark:border-red-400/20 dark:text-red-300">
                                                                {{ __('Reject') }}
                                                            </button>
                                                        </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="rounded-2xl border border-dashed border-[rgba(15,23,42,0.12)] px-4 py-4 text-sm leading-6 text-[var(--color-text-muted)] dark:border-white/[0.12]">
                                                        {{ __('Waiting for the student to submit the payment reference and screenshot.') }}
                                                    </div>
                                                @endif
                                            @else
                                                @if ($payment->approver)
                                                    <p class="text-sm font-medium text-[var(--color-text-muted)]">{{ __('Reviewed by') }} <span class="text-[var(--color-text-primary)]">{{ $payment->approver->name }}</span></p>
                                                @endif
                                                @if ($payment->review_notes)
                                                    <div class="whitespace-pre-line rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.03)] px-4 py-4 text-sm leading-6 text-[var(--color-text-muted)] dark:border-white/[0.08] dark:bg-white/[0.03]">
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
