<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Success') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p class="mb-4">Your payment was received by Stripe. Enrollment will be applied once confirmed by our system.</p>
            <p><a class="text-blue-600" href="{{ url('/courses') }}">Back to courses</a></p>
        </div>
    </div>
</x-app-layout>
