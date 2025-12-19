<x-public-layout :title="$course->title" :metaDescription="str($course->description)->limit(160)">
    <div class="space-y-8">
        <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-6 md:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900">{{ $course->title }}</h1>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @if ($course->is_free || (float)$course->price == 0.0)
                            <span class="inline-flex items-center px-3 py-1 rounded bg-green-100 text-green-700 text-xs font-medium">Free</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded bg-blue-100 text-blue-700 text-xs font-medium">
                                {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded bg-gray-100 text-gray-700 text-xs font-medium">
                            {{ strtoupper($course->language) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-6 space-y-2">
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 rounded bg-[var(--color-primary)] text-white text-base font-semibold hover:opacity-90">Login to Enroll</a>
                @else
                    @if ($isEnrolled)
                        @if (!empty($firstLesson))
                            <a href="{{ route('lessons.show', [$course, $firstLesson]) }}" class="inline-flex items-center px-6 py-3 rounded bg-[var(--color-primary)] text-white text-base font-semibold hover:opacity-90">Continue Learning</a>
                        @endif
                        <p class="text-sm text-green-700 font-medium">You are enrolled</p>
                        <p class="text-sm text-gray-700">Progress: {{ $progressPercent }}%</p>
                    @else
                        @if ($course->is_free || (float)$course->price == 0.0)
                            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-6 py-3 rounded bg-[var(--color-primary)] text-white text-base font-semibold hover:opacity-90">Enroll</button>
                            </form>
                        @else
                            @if ($hasAnyPaymentMethod)
                                @if ($isStripeEnabled)
                                    <form action="{{ route('payments.checkout', $course) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-6 py-3 rounded bg-[var(--color-primary)] text-white text-base font-semibold hover:opacity-90">Buy Course</button>
                                    </form>
                                @endif
                                @if ($isPayPalEnabled)
                                    <form action="{{ route('payments.paypal.checkout', $course) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-6 py-3 rounded bg-yellow-600 text-white text-base font-semibold hover:opacity-90">Pay with PayPal</button>
                                    </form>
                                @endif
                                @if ($hasManualPayment)
                                    <form action="{{ route('payments.manual.start', $course) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-6 py-3 rounded bg-gray-800 text-white text-base font-semibold hover:opacity-90">Manual Payment</button>
                                    </form>
                                @endif
                            @else
                                <p class="text-sm text-red-600 font-medium">
                                    Payments are currently disabled. Please contact the instructor.
                                </p>
                            @endif
                        @endif
                    @endif
                @endguest
            </div>
        </div>

        @if ($course->thumbnail_path)
            <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full max-w-4xl rounded-xl shadow-lg ring-1 ring-gray-100">
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">About this course</h2>
                @if (!empty($course->description))
                    <div class="text-gray-700 leading-relaxed">
                        {!! nl2br(e($course->description)) !!}
                    </div>
                @else
                    <div class="text-gray-600 text-sm">
                        No description provided.
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Lessons</h2>
                @if (!empty($lessons) && $lessons->count())
                    <ul class="divide-y divide-gray-200">
                        @foreach ($lessons as $l)
                            <li class="py-3 flex items-center justify-between">
                                <a href="{{ route('lessons.show', [$course, $l]) }}" class="text-[var(--color-secondary)] hover:underline">{{ $l->title }}</a>
                                <span class="text-xs text-gray-500">#{{ $l->position }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="rounded-lg border border-dashed p-6 text-center">
                        <div class="text-3xl mb-2">🗒️</div>
                        <p class="text-gray-700 font-medium">No lessons yet</p>
                        <p class="text-gray-500 text-sm">Lessons will appear here once added.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-public-layout>
