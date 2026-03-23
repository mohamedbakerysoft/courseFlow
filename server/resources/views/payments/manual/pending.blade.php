<x-app-layout>
    @php
        $backRoute = (($payment->course?->product_type ?? \App\Models\Course::TYPE_COURSE) === \App\Models\Course::TYPE_BOOK)
            ? route('books.show', $payment->course)
            : route('courses.show', $payment->course);
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Manual Payment') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ $payment->course?->title }}</h3>
                    <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Complete the manual payment, then submit your transfer reference and screenshot for admin review.') }}</p>
                </div>

                @if (!empty($manualInstructions))
                    <div class="rounded-xl border border-[var(--color-secondary)]/10 bg-[var(--color-surface)] p-4">
                        <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Payment information') }}</p>
                        <div class="cf-lesson-richtext mt-3 text-sm text-[var(--color-text-primary)]">
                            {!! $manualInstructions !!}
                        </div>
                    </div>
                @endif

                <div class="rounded-xl border border-[var(--color-secondary)]/10 bg-[var(--color-surface)] p-4">
                    <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Request status') }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        @if ($payment->status === \App\Models\Payment::STATUS_PAID)
                            <span class="cf-badge">{{ __('Approved') }}</span>
                        @elseif ($payment->status === \App\Models\Payment::STATUS_FAILED)
                            <span class="cf-badge-muted !border-[var(--color-error)]/20 !bg-[var(--color-error)]/10 !text-[var(--color-error)]">{{ __('Rejected') }}</span>
                        @else
                            <span class="cf-badge-muted">{{ __('Pending review') }}</span>
                        @endif
                        <span class="text-sm text-[var(--color-text-muted)]">{{ __('Internal reference') }}: {{ $payment->external_reference }}</span>
                    </div>

                    @if ($payment->review_notes)
                        <div class="mt-4 rounded-lg border border-[var(--color-secondary)]/10 bg-white px-4 py-3 text-sm text-[var(--color-text-primary)]">
                            <p class="font-medium">{{ __('Admin note') }}</p>
                            <p class="mt-2 whitespace-pre-line">{{ $payment->review_notes }}</p>
                        </div>
                    @endif
                </div>

                @if ($payment->status === \App\Models\Payment::STATUS_PENDING)
                    <form method="POST" action="{{ route('payments.manual.submit', $payment) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Payment reference / transfer code') }}</label>
                            <textarea name="payment_reference" rows="4" class="mt-2 w-full rounded-xl border border-[var(--color-secondary)]/20 px-4 py-3">{{ old('payment_reference', $payment->payment_reference) }}</textarea>
                            @error('payment_reference')
                                <p class="mt-2 text-sm text-[var(--color-error)]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Transfer screenshot / receipt image') }}</label>
                            <input name="proof_image" type="file" accept="image/*" class="mt-2 block w-full rounded-xl border border-[var(--color-secondary)]/20 px-4 py-3">
                            @error('proof_image')
                                <p class="mt-2 text-sm text-[var(--color-error)]">{{ $message }}</p>
                            @enderror

                            @if ($payment->proof_url)
                                <div class="mt-3">
                                    <img src="{{ $payment->proof_url }}" alt="{{ __('Submitted payment proof') }}" class="h-40 rounded-xl border border-[var(--color-secondary)]/10 object-cover">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="cf-button-primary">
                            {{ __('I Confirm The Payment Is Done') }}
                        </button>
                    </form>
                @elseif ($payment->status === \App\Models\Payment::STATUS_PAID)
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-700">
                        {{ __('Your payment has been approved. You now have access to this course.') }}
                    </div>
                @else
                    <div class="rounded-xl border border-[var(--color-error)]/20 bg-[var(--color-error)]/10 px-4 py-4 text-sm text-[var(--color-text-primary)]">
                        {{ __('This payment request was rejected. You can submit a new manual payment request from the course page if needed.') }}
                    </div>
                @endif

                <p><a class="text-[var(--color-primary)] hover:underline" href="{{ $backRoute }}">{{ __('Back to course') }}</a></p>
            </div>
        </div>
    </div>
</x-app-layout>
