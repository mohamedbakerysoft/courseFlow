<x-public-layout :title="$course->title" :metaDescription="str($course->description)->limit(160)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Courses'), 'url' => route('courses.index')],
            ['label' => $course->title],
        ]" />

        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-medium text-[var(--color-primary)] hover:underline">
                {{ __('Back to courses') }}
            </a>
        </div>

        <section class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div class="space-y-4">
                <div class="space-y-3">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[var(--color-text-primary)]">
                        {{ $course->title }}
                    </h1>
                    @if (!empty($course->description))
                        <p class="text-sm sm:text-base text-[var(--color-text-muted)] max-w-2xl">
                            {{ str($course->description)->limit(180) }}
                        </p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 text-sm text-[var(--color-text-muted)]">
                        @if ($course->instructor)
                            <span class="font-medium text-[var(--color-text-primary)]">
                                {{ $course->instructor->name }}
                            </span>
                        @elseif (!empty($instructorName))
                            <span class="font-medium text-[var(--color-text-primary)]">
                                {{ $instructorName }}
                            </span>
                        @endif
                        <span class="inline-flex items-center rounded-full bg-[var(--color-secondary)]/10 px-3 py-1 text-xs font-medium text-[var(--color-text-muted)]">
                            {{ $lessons->count() }} {{ ($appLocale ?? app()->getLocale()) === 'ar' ? 'دروس' : Str::plural(__('lesson'), $lessons->count()) }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-[var(--color-secondary)]/10 px-3 py-1 text-xs font-medium text-[var(--color-text-muted)]">
                            {{ strtoupper($course->language) }}
                        </span>
                        @if ($course->is_free || (float)$course->price == 0.0)
                            <span class="inline-flex items-center rounded-full bg-[var(--color-primary)] text-white px-3 py-1 text-xs font-semibold">
                                {{ __('Free') }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-[var(--color-primary)] text-white px-3 py-1 text-xs font-semibold">
                                {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-2">
                    @auth
                        @if ($isEnrolled)
                            <p class="text-sm text-[var(--color-accent)] font-medium">
                                {{ __('You are enrolled') }}
                            </p>
                            <p class="text-sm text-[var(--color-text-muted)]">
                                {{ __('Progress') }}: {{ $progressPercent }}%
                            </p>
                        @endif
                    @endauth
                </div>

            </div>

            <div class="space-y-4">
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-[var(--color-secondary)]/10">
                    <div class="relative aspect-video">
                        @if ($course->thumbnail_path)
                            <img src="{{ asset($course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[var(--color-primary)]/10 via-white to-[var(--color-primary)]/5 flex items-center justify-center text-sm text-[var(--color-text-muted)]">
                                {{ __('Course preview') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 space-y-4">
                    <h2 class="text-xl font-semibold text-[var(--color-text-primary)]">
                        {{ __('What you will learn') }}
                    </h2>
                    <ul class="space-y-2 text-sm text-[var(--color-text-muted)]">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[var(--color-primary)]"></span>
                            <span>{{ __('Understand the key concepts covered in this course.') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[var(--color-primary)]"></span>
                            <span>{{ __('Follow the lessons step by step at your own pace.') }}</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[var(--color-primary)]"></span>
                            <span>{{ __('Apply what you learn directly inside your own projects.') }}</span>
                        </li>
                    </ul>
                    @if (!empty($course->description))
                        <div class="pt-4 border-t border-[var(--color-secondary)]/10">
                            <h3 class="text-sm font-semibold text-[var(--color-text-primary)] mb-2">
                                {{ __('About this course') }}
                            </h3>
                            <div class="text-sm text-[var(--color-text-muted)] leading-relaxed">
                                {!! nl2br(e($course->description)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6">
                    <h2 class="text-xl font-semibold text-[var(--color-text-primary)] mb-3">
                        {{ __('Lessons preview') }}
                    </h2>
                    @if (!empty($lessons) && $lessons->count())
                        <ul class="divide-y divide-[var(--color-secondary)]/20">
                            @foreach ($lessons as $l)
                                <li class="py-3 flex items-center justify-between gap-4">
                                    @auth
                                        @if ($isEnrolled)
                                            <a href="{{ route('lessons.show', [$course, $l]) }}" class="text-sm text-[var(--color-secondary)] hover:underline">
                                                {{ $l->title }}
                                            </a>
                                        @else
                                            <div class="flex items-center gap-2 text-sm text-[var(--color-text-muted)]">
                                                <svg class="h-4 w-4 text-[var(--color-text-muted)]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                    <path d="M8 11V9a4 4 0 118 0v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <rect x="6" y="11" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5" />
                                                </svg>
                                                <span>{{ $l->title }}</span>
                                            </div>
                                        @endif
                                    @endauth
                                    @guest
                                        <div class="flex items-center gap-2 text-sm text-[var(--color-text-muted)]">
                                            <svg class="h-4 w-4 text-[var(--color-text-muted)]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M8 11V9a4 4 0 118 0v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <rect x="6" y="11" width="12" height="9" rx="2" stroke="currentColor" stroke-width="1.5" />
                                            </svg>
                                            <span>{{ $l->title }}</span>
                                        </div>
                                    @endguest
                                    <span class="text-xs text-[var(--color-text-muted)]">
                                        #{{ $l->position }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="rounded-lg border border-dashed p-6 text-center">
                            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-secondary)]/10 text-[var(--color-text-muted)]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <rect x="4" y="5" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5" />
                                    <path d="M8 9h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </div>
                            <p class="text-[var(--color-text-muted)] font-medium">
                                {{ __('Lessons will appear once the course is published.') }}
                            </p>
                            <p class="text-[var(--color-text-muted)] text-sm">
                                {{ __('Once published, lessons will appear here.') }}
                            </p>
                        </div>
                    @endif
                    @auth
                        @if ($isEnrolled)
                            <p class="mt-4 text-xs text-[var(--color-text-muted)]">
                                {{ __('Your course progress') }}: {{ $progressPercent }}%
                            </p>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky lg:top-24 space-y-4">
                    <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">
                                    {{ $course->title }}
                                </p>
                                <p class="text-xs text-[var(--color-text-muted)]">
                                    {{ __('Instant access to all lessons after enrollment.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if ($course->is_free || (float)$course->price == 0.0)
                                    <p class="text-lg font-semibold text-[var(--color-primary)]">
                                        {{ __('Free') }}
                                    </p>
                                @else
                                    <p class="text-lg font-semibold text-[var(--color-text-primary)]">
                                        {{ number_format((float)$course->price, 2) }} {{ $course->currency }}
                                    </p>
                                @endif
                                <p class="text-xs text-[var(--color-text-muted)]">
                                    {{ $lessons->count() }} {{ ($appLocale ?? app()->getLocale()) === 'ar' ? 'دروس' : Str::plural(__('lesson'), $lessons->count()) }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                @guest
                    <a href="{{ route('login') }}" class="inline-flex w-full justify-center items-center px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                        {{ __('Login to Enroll') }}
                    </a>
                            @else
                                @if ($isEnrolled)
                                    @if (!empty($firstLesson))
                                        <a href="{{ route('lessons.show', [$course, $firstLesson]) }}" class="inline-flex w-full justify-center items-center px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                            {{ __('Continue learning') }}
                                        </a>
                                    @endif
                                    <p class="text-xs text-gray-600 text-center">
                                        {{ __('You are already enrolled in this course.') }}
                                    </p>
                                @else
                                    @if ($course->is_free || (float)$course->price == 0.0)
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="inline-flex w-full justify-center items-center px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                                {{ __('Enroll') }}
                                            </button>
                                        </form>
                                    @else
                                        @if ($hasAnyPaymentMethod)
                                            @if ($isStripeEnabled)
                                                <form action="{{ route('payments.checkout', $course) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="inline-flex w-full justify-center items-center px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                                        {{ __('Buy Course') }}
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($isPayPalEnabled)
                                                <form action="{{ route('payments.paypal.checkout', $course) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="inline-flex w-full justify-center items-center px-6 py-3 rounded-full bg-[var(--color-accent)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-accent)]">
                                                        {{ __('Pay with PayPal') }}
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($hasManualPayment)
                                                <form action="{{ route('payments.manual.start', $course) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="inline-flex w-full justify-center items-center px-6 py-3 rounded-full bg-[var(--color-secondary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-secondary)]">
                                                        {{ __('Manual Payment') }}
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <p class="text-xs text-[var(--color-error)] text-center font-medium">
                                                {{ __('Payments are currently disabled. Please contact the instructor.') }}
                                            </p>
                                        @endif
                                    @endif
                                @endif
                            @endguest
                        </div>

                        <p class="text-xs text-gray-500">
                            {{ __('Secure payments and instant course access after enrollment.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 sm:p-8 space-y-4">
            <h2 class="text-xl font-semibold text-gray-900">
                {{ __('Instructor') }}
            </h2>
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                @php
                    $profileImage = ($course->instructor && $course->instructor->profile_image_path)
                        ? asset($course->instructor->profile_image_path)
                        : ($instructorImageUrl ?? null);
                    $displayName = $instructorName !== '' ? $instructorName : ($course->instructor->name ?? '');
                    $displayBio = $instructorBio !== '' ? $instructorBio : ($course->instructor->bio ?? '');
                @endphp
                @if (!empty($profileImage))
                    <img src="{{ $profileImage }}" alt="{{ $displayName }}" class="w-20 h-20 rounded-full object-cover ring-4 ring-[var(--color-primary)]/10">
                @else
                    <div class="w-20 h-20 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center text-[var(--color-primary)] text-lg">
                        {{ Str::substr($displayName ?? '', 0, 1) }}
                    </div>
                @endif
                <div class="space-y-2 text-center sm:text-start">
                    <p class="text-base font-semibold text-gray-900">
                        {{ $displayName }}
                    </p>
                    @if (!empty($displayBio))
                        <p class="text-sm text-gray-600">
                            {{ $displayBio }}
                        </p>
                    @endif
                </div>
            </div>
        </section>
    </div>
</x-public-layout>
