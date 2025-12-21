<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Manual Payment Pending') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            @if (!empty($manualInstructions))
                <div class="mb-4 text-sm text-[var(--color-text-primary)] whitespace-pre-line">
                    {{ $manualInstructions }}
                </div>
            @endif
            <p class="mb-4">Your manual payment request is pending approval. Please follow the instructions above and wait for confirmation.</p>
            <p class="mb-4 text-sm text-[var(--color-text-primary)]">Reference: {{ $payment->external_reference }}</p>
            <p class="text-sm text-[var(--color-text-muted)] mb-2">Approval is typically processed within 24–48 hours.</p>
            <p><a class="text-[var(--color-primary)] hover:underline" href="{{ route('courses.show', $payment->course) }}">Back to course</a></p>
        </div>
    </div>
</x-app-layout>
