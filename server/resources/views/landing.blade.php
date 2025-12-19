<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    <div class="space-y-16">
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div class="space-y-6">
                <p class="text-sm font-semibold text-[var(--color-accent)] tracking-wide uppercase">
                    Online learning platform
                </p>
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-gray-900">
                    {{ $heroTitle }}
                </h1>
                <p class="text-lg text-gray-600 max-w-xl">
                    {{ $heroSubtitle }}
                </p>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center px-6 py-3 rounded-lg bg-[var(--color-primary)] text-white font-semibold shadow-sm hover:opacity-90 transition">
                        Browse Courses
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-5 py-3 rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-50 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center px-5 py-3 rounded-lg border border-gray-200 text-gray-800 hover:bg-gray-50 transition">
                            Login
                        </a>
                    @endauth
                </div>
                @if ($instructor)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        @if ($instructorImageUrl)
                            <img src="{{ $instructorImageUrl }}" alt="{{ $instructor->name }}" class="w-10 h-10 rounded-full object-cover">
                        @elseif ($instructor->profile_image_path)
                            <img src="{{ asset($instructor->profile_image_path) }}" alt="{{ $instructor->name }}" class="w-10 h-10 rounded-full object-cover">
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">{{ $instructor->name }}</p>
                            @if ($instructor->bio)
                                <p class="text-gray-500 line-clamp-1">{{ $instructor->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-[var(--color-primary)]/10 via-[var(--color-accent)]/10 to-[var(--color-secondary)]/10 rounded-3xl blur-2xl"></div>
                <div class="relative bg-white/80 backdrop-blur rounded-3xl shadow-xl ring-1 ring-gray-100 p-6 sm:p-8 flex flex-col items-center gap-4">
                    @if ($instructorImageUrl || ($instructor && $instructor->profile_image_path))
                        <img src="{{ $instructorImageUrl ?? asset($instructor->profile_image_path) }}"
                             alt="{{ $instructor?->name }}"
                             class="w-28 h-28 rounded-full object-cover ring-4 ring-[var(--color-primary)]/10">
                    @else
                        <div class="w-28 h-28 rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-secondary)] flex items-center justify-center text-3xl text-white">
                            🎓
                        </div>
                    @endif
                    <div class="text-center space-y-1">
                        <p class="text-sm font-semibold text-[var(--color-secondary)] tracking-wide uppercase">
                            Learn with confidence
                        </p>
                        <p class="text-sm text-gray-600">
                            Clean Laravel + Tailwind stack, payments-ready and optimized for selling your courses.
                        </p>
                    </div>
                    @if (!empty($instructorLinks))
                        <div class="flex flex-wrap justify-center gap-3 text-sm">
                            @foreach ($instructorLinks as $label => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                    <span class="mr-1">•</span> {{ ucfirst($label) }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            <div class="space-y-4">
                <h2 class="text-2xl font-semibold text-gray-900">
                    Instructor
                </h2>
                @if ($instructor)
                    <p class="text-gray-600 leading-relaxed">
                        {{ $instructor->bio ?? 'Instructor profile is ready to introduce your expertise.' }}
                    </p>
                @else
                    <p class="text-gray-600 leading-relaxed">
                        Introduce your instructor profile to build trust with students.
                    </p>
                @endif
            </div>
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ($features as $feature)
                    <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-5 flex flex-col gap-3">
                        <div class="w-10 h-10 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center text-xl">
                            {{ $feature['icon'] }}
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ $feature['title'] }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $feature['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">
                        Featured courses
                    </h2>
                    <p class="text-sm text-gray-600">
                        Real-world demo courses are ready to explore or replace with your own.
                    </p>
                </div>
                <a href="{{ route('courses.index') }}" class="hidden sm:inline-flex items-center text-sm font-semibold text-[var(--color-primary)] hover:underline">
                    View all courses
                </a>
            </div>
            @if ($featuredCourses->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($featuredCourses as $course)
                        <x-course.card :course="$course" ctaLabel="View course" />
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-100 p-10 text-center">
                    <div class="text-4xl mb-3">📚</div>
                    <p class="text-gray-800 font-semibold">No courses yet</p>
                    <p class="text-gray-500 text-sm mt-1">
                        As soon as you publish courses, they will appear here.
                    </p>
                </div>
            @endif
        </section>

        <section class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] rounded-3xl px-8 py-10 flex flex-col md:flex-row items-center justify-between gap-6 text-white">
            <div class="space-y-2">
                <h2 class="text-2xl font-semibold">
                    Start learning today
                </h2>
                <p class="text-sm text-white/80 max-w-xl">
                    Browse the demo catalog or plug in your own courses and start accepting students.
                </p>
            </div>
            <div>
                <a href="{{ route('courses.index') }}"
                   class="inline-flex items-center px-6 py-3 rounded-lg bg-white text-[var(--color-secondary)] font-semibold shadow-sm hover:bg-gray-100 transition">
                    Browse Courses
                </a>
            </div>
        </section>
    </div>
</x-public-layout>

