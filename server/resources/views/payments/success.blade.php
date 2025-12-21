<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Payment Success') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p class="mb-4">Your payment was received by Stripe. Enrollment will be applied once confirmed by our system.</p>
            <p class="text-sm text-[var(--color-text-muted)] mb-2">You can safely close this page. We will redirect you back once confirmed.</p>
            <p><a class="text-[var(--color-primary)] hover:underline" href="{{ url('/courses') }}">Back to courses</a></p>
        </div>
    </div>
</x-app-layout>
