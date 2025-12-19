<x-public-layout :title="$course->title" :metaDescription="str($course->description)->limit(160)">
    <article class="max-w-3xl">
        <h1 class="text-3xl font-semibold mb-4">{{ $course->title }}</h1>
        @if ($course->thumbnail_path)
            <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full max-w-xl rounded mb-6">
        @endif
        <p class="mb-4">
            @if ($course->is_free || (float)$course->price == 0.0)
                <span class="inline-block px-3 py-1 rounded bg-green-100 text-green-700">Free</span>
            @else
                <span class="inline-block px-3 py-1 rounded bg-blue-100 text-blue-700">
                    {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                </span>
            @endif
        </p>
        <p class="text-sm text-gray-600 mb-6">Language: {{ strtoupper($course->language) }}</p>
        @auth
            @if ($isEnrolled)
                <p class="text-sm text-gray-800 mb-4">Progress: {{ $progressPercent }}%</p>
            @endif
        @endauth
        @if (!empty($course->description))
            <div class="prose max-w-none">
                {!! nl2br(e($course->description)) !!}
            </div>
        @endif
        <div class="mt-8">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-gray-800 text-white">Login to Enroll</a>
            @else
                @if ($isEnrolled)
                    <span class="inline-block px-3 py-2 rounded bg-green-600 text-white">You are enrolled</span>
                @else
                    @if ($course->is_free || (float)$course->price == 0.0)
                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded bg-black text-white">Enroll</button>
                        </form>
                    @else
                        <form action="{{ route('payments.checkout', $course) }}" method="POST" class="inline-block mr-2">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded bg-blue-700 text-white">Buy Course</button>
                        </form>
                        <form action="{{ route('payments.paypal.checkout', $course) }}" method="POST" class="inline-block mr-2">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded bg-yellow-700 text-white">Pay with PayPal</button>
                        </form>
                        <form action="{{ route('payments.manual.start', $course) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded bg-gray-700 text-white">Manual Payment</button>
                        </form>
                    @endif
                @endif
            @endguest
        </div>
    </article>
</x-public-layout>
