<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Canceled') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p class="mb-4">Your payment was canceled. You are not enrolled in this course.</p>
            <p class="text-sm text-gray-600 mb-2">If this was a mistake, you can start the checkout again.</p>
            <p><a class="text-blue-600" href="{{ route('courses.show', $course) }}">Back to course</a></p>
        </div>
    </div>
</x-app-layout>
