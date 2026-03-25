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
                <div class="overflow-hidden rounded-[28px] border border-white/10 bg-[#111111] shadow-[0_30px_80px_rgba(0,0,0,0.28)]">
                    <div class="grid gap-4 border-b border-white/10 bg-[linear-gradient(135deg,rgba(255,193,7,0.14),rgba(255,193,7,0.03)_45%,rgba(255,255,255,0)_100%)] px-5 py-5 sm:grid-cols-3 sm:px-7">
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-white/45">{{ __('Queue size') }}</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ $manual_payment_requests->total() }}</p>
                            <p class="mt-1 text-sm text-white/55">{{ __('Requests currently awaiting review or audit history.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-amber-300/20 bg-amber-300/10 px-4 py-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-amber-100/70">{{ __('Pending review') }}</p>
                            <p class="mt-2 text-3xl font-bold text-amber-100">
                                {{ $manual_payment_requests->getCollection()->where('status', \App\Models\Payment::STATUS_PENDING)->count() }}
                            </p>
                            <p class="mt-1 text-sm text-amber-100/70">{{ __('Requests that still need a manual decision.') }}</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-300/20 bg-emerald-300/10 px-4 py-4">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-100/70">{{ __('Ready to approve') }}</p>
                            <p class="mt-2 text-3xl font-bold text-emerald-100">
                                {{ $manual_payment_requests->getCollection()->filter(fn ($payment) => $payment->status === \App\Models\Payment::STATUS_PENDING && $payment->is_manual_submission_complete)->count() }}
                            </p>
                            <p class="mt-1 text-sm text-emerald-100/70">{{ __('Submitted reference and proof are both available.') }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                    <table class="cf-table min-w-[1120px] border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-[#151515]">
                                <th class="!px-8 !py-5">{{ __('Student') }}</th>
                                <th class="!px-6 !py-5">{{ __('Course') }}</th>
                                <th class="!px-6 !py-5">{{ __('Reference') }}</th>
                                <th class="!px-6 !py-5">{{ __('Proof') }}</th>
                                <th class="!px-6 !py-5">{{ __('Status') }}</th>
                                <th class="!px-8 !py-5">{{ __('Review') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#111111]">
                            @foreach ($manual_payment_requests as $payment)
                                <tr class="align-top transition hover:bg-white/[0.03]">
                                    <td class="!px-8 !py-7 border-t border-white/10">
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[var(--color-primary)] text-sm font-bold text-[var(--color-secondary)] shadow-[0_12px_30px_rgba(255,193,7,0.22)]">
                                                {{ \Illuminate\Support\Str::of($payment->user?->name ?? 'U')->substr(0, 1)->upper() }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-white">{{ $payment->user?->name ?? '-' }}</p>
                                                <p class="mt-1 text-sm text-white/60">{{ $payment->user?->email ?? '-' }}</p>
                                                @if ($payment->submitted_at)
                                                    <p class="mt-3 inline-flex rounded-full border border-white/10 bg-white/[0.04] px-3 py-1 text-[11px] font-medium uppercase tracking-[0.18em] text-white/45">
                                                        {{ __('Submitted') }} {{ $payment->submitted_at->format('M d, H:i') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="!px-6 !py-7 border-t border-white/10">
                                        <div class="max-w-[250px]">
                                            <p class="font-semibold leading-8 text-white">{{ $payment->course?->title ?? '-' }}</p>
                                            <div class="mt-3 inline-flex items-center rounded-full border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 px-3 py-1.5 text-sm font-semibold text-[var(--color-primary)]">
                                                {{ number_format((float) $payment->amount, 2) }} {{ $payment->currency }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="!px-6 !py-7 border-t border-white/10">
                                        <div class="max-w-[260px] rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-4">
                                            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-white/40">{{ __('Transfer reference') }}</p>
                                            <div class="mt-3 break-words whitespace-pre-line font-mono text-sm leading-7 text-white/72">
                                                {{ $payment->payment_reference ?: __('Not submitted yet') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="!px-6 !py-7 border-t border-white/10">
                                        @if ($payment->proof_url)
                                            <a href="{{ $payment->proof_url }}" target="_blank" rel="noopener" class="group block">
                                                <div class="relative w-fit overflow-hidden rounded-[22px] border border-white/10 bg-white/[0.04] p-2 transition group-hover:border-[var(--color-primary)]/40 group-hover:bg-white/[0.06]">
                                                    <img src="{{ $payment->proof_url }}" alt="{{ __('Payment proof') }}" class="h-24 w-24 rounded-2xl object-cover">
                                                    <div class="pointer-events-none absolute inset-x-2 bottom-2 rounded-b-2xl bg-gradient-to-t from-black/70 via-black/15 to-transparent px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-white/85 opacity-0 transition group-hover:opacity-100">
                                                        {{ __('Open proof') }}
                                                    </div>
                                                </div>
                                            </a>
                                        @else
                                            <div class="inline-flex min-h-24 min-w-24 items-center justify-center rounded-[22px] border border-dashed border-white/10 bg-white/[0.02] px-4 text-center text-sm leading-6 text-white/45">
                                                {{ __('No proof yet') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="!px-6 !py-7 border-t border-white/10">
                                        <div class="space-y-3">
                                        @if ($payment->status === \App\Models\Payment::STATUS_PAID)
                                            <span class="inline-flex rounded-full border border-emerald-300/20 bg-emerald-300/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-emerald-200">{{ __('Approved') }}</span>
                                        @elseif ($payment->status === \App\Models\Payment::STATUS_FAILED)
                                            <span class="inline-flex rounded-full border border-red-400/20 bg-red-500/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-red-300">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="inline-flex rounded-full border border-white/10 bg-white/[0.06] px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-white/70">{{ __('Pending') }}</span>
                                        @endif

                                            <p class="text-sm leading-7 text-white/55">
                                                {{ $payment->is_manual_submission_complete ? __('Ready for a final decision.') : __('Waiting for the student to finish submission.') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="!px-8 !py-7 border-t border-white/10">
                                        <div class="space-y-3">
                                            @if ($payment->status === \App\Models\Payment::STATUS_PENDING)
                                                @if ($payment->is_manual_submission_complete)
                                                    <div class="max-w-[320px] rounded-[24px] border border-white/10 bg-white/[0.04] p-4">
                                                        <p class="mb-4 text-[11px] font-semibold uppercase tracking-[0.22em] text-white/40">{{ __('Review actions') }}</p>
                                                        <div class="flex flex-col gap-3">
                                                        <form action="{{ route('dashboard.payments.approve', $payment) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="cf-button-primary !w-full !justify-center !rounded-2xl !px-4 !py-3 text-[11px] font-bold uppercase tracking-[0.22em] shadow-[0_14px_32px_rgba(255,193,7,0.18)]">
                                                                {{ __('Approve') }}
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('dashboard.payments.reject', $payment) }}" method="POST" class="space-y-2">
                                                            @csrf
                                                            <textarea name="review_notes" rows="3" class="w-full rounded-2xl border border-white/10 bg-[#0f0f0f] px-4 py-3 text-sm text-white placeholder:text-white/30 focus:border-[var(--color-primary)] focus:outline-none focus:ring-0" placeholder="{{ __('Reason for rejection') }}"></textarea>
                                                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-[11px] font-bold uppercase tracking-[0.22em] text-red-300 transition hover:bg-red-500/16">
                                                                {{ __('Reject') }}
                                                            </button>
                                                        </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="max-w-[320px] rounded-[24px] border border-dashed border-white/10 bg-white/[0.03] px-4 py-4 text-sm leading-7 text-white/52">
                                                        {{ __('Waiting for the student to submit the payment reference and screenshot.') }}
                                                    </div>
                                                @endif
                                            @else
                                                @if ($payment->approver)
                                                    <p class="text-sm font-medium text-white/65">{{ __('Reviewed by') }} <span class="text-white">{{ $payment->approver->name }}</span></p>
                                                @endif
                                                @if ($payment->review_notes)
                                                    <div class="max-w-[320px] whitespace-pre-line rounded-2xl border border-white/10 bg-white/[0.04] px-4 py-4 text-sm leading-7 text-white/60">
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
