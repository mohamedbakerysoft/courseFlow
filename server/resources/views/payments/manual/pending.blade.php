<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manual Payment Pending') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p class="mb-4">Your manual payment request is pending approval. Please follow the instructions provided by the instructor and wait for confirmation.</p>
            <p class="mb-4 text-sm text-gray-700">Reference: {{ $payment->external_reference }}</p>
            <p><a class="text-blue-600" href="{{ route('courses.show', $payment->course) }}">Back to course</a></p>
        </div>
    </div>
</x-app-layout>
