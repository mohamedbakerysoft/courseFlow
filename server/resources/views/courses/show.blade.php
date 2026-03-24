<x-public-layout :title="$course->title" :metaDescription="str($course->description)->limit(160)">
    @php
        $displayPrice = $course->is_free || (float) $course->price == 0.0
            ? __('Free')
            : number_format((float) $course->price, 2).' '.$course->currency;
        $isGuest = ! auth()->check();
        $displayName = $instructorName !== '' ? $instructorName : ($course->instructor->name ?? '');
        $displayBio = $instructorBio !== '' ? $instructorBio : ($course->instructor->bio ?? '');
        $profileImage = !empty($instructorImageUrl)
            ? $instructorImageUrl
            : ($course->instructor?->profile_image_url ?? \App\Support\MediaAsset::avatarFallback($displayName));
        $profileImageFallback = $course->instructor?->profile_image_fallback_url ?? \App\Support\MediaAsset::avatarFallback($displayName);
        $thumbnail = $course->thumbnail_url;
        $thumbnailFallback = $course->thumbnail_fallback_url;
    @endphp

    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Courses'), 'url' => route('courses.index')],
            ['label' => $course->title],
        ]" />

        <div class="mt-6 space-y-6">
            <x-public.demo-notice />

            <div class="grid gap-6 xl:grid-cols-[minmax(0,0.92fr),minmax(0,1.08fr)] xl:items-start xl:gap-8">
                <div class="space-y-5">
                    <div class="space-y-4">
                        <h1 class="cf-display text-4xl sm:text-5xl">{{ $course->title }}</h1>
                        @if (!empty($course->description))
                            <p class="cf-subheading max-w-none">{{ $course->description }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="cf-panel-soft cf-course-hero-soft px-4 py-3">
                            <p class="font-semibold text-[var(--color-text-primary)]">{{ $course->instructor?->name ?? $instructorName }}</p>
                            <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Instructor') }}</p>
                        </div>
                        <div class="cf-panel-soft cf-course-hero-soft px-4 py-3">
                            <p class="font-semibold text-[var(--color-text-primary)]">{{ __('Self-paced format') }}</p>
                            <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Learn through a focused curriculum at your own pace') }}</p>
                        </div>
                        @unless ($isEnrolled)
                            <div class="cf-panel-soft cf-course-hero-soft px-4 py-3">
                                <p class="font-semibold text-[var(--color-text-primary)]">{{ __('Enrollment ready') }}</p>
                                <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Card, PayPal, or manual payment support') }}</p>
                            </div>
                        @endunless
                    </div>
                    @auth
                        @if ($isEnrolled)
                            <div class="rounded-2xl border border-[var(--color-primary)] bg-[var(--color-primary)]/10 px-4 py-3 text-sm text-[var(--color-primary)]">
                                {{ __('You are enrolled') }}. {{ __('Progress') }}: {{ $progressPercent }}%
                            </div>
                        @endif
                    @endauth
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('courses.index') }}" class="cf-button-ghost !px-4 !py-2">{{ __('Back to courses') }}</a>
                        <span class="cf-chip">{{ $displayPrice }}</span>
                        <span class="cf-chip">{{ $lessons->count() }} {{ Str::plural('lesson', $lessons->count()) }}</span>
                        <span class="cf-chip">{{ strtoupper($course->language) }}</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="cf-panel cf-course-hero-panel overflow-hidden">
                        <div class="relative">
                            <img
                                src="{{ $thumbnail }}"
                                alt="{{ $course->title }}"
                                class="aspect-[16/10] w-full object-cover xl:aspect-[16/9]"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ $thumbnailFallback }}';"
                            >
                            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(180deg,rgba(7,10,20,0)_35%,rgba(7,10,20,0.18)_100%)]"></div>
                        </div>
                    </div>

                    <div class="cf-panel cf-course-hero-panel px-6 py-6 sm:px-8">
                        @if ($isEnrolled)
                            <div class="space-y-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('You are enrolled') }}</p>
                                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Continue your learning path, review completed material, and jump back into the next lesson.') }}</p>
                                    </div>
                                    <div class="sm:text-right">
                                        <p class="text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $progressPercent }}%</p>
                                        <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ __('Course progress') }}</p>
                                    </div>
                                </div>

                                @if ($nextLesson)
                                    <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="cf-button-primary w-full">{{ count($completedLessonIds) ? __('Continue learning') : __('Start learning') }}</a>
                                @endif
                            </div>
                        @else
                            <div class="space-y-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $course->title }}</p>
                                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Review the curriculum, choose a payment method, and start learning right away.') }}</p>
                                    </div>
                                    <div class="sm:text-right">
                                        <p class="text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $displayPrice }}</p>
                                        <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $lessons->count() }} {{ Str::plural('lesson', $lessons->count()) }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-2">
                                    @if ($isGuest)
                                        <a href="{{ route('login') }}" class="cf-button-primary w-full">{{ __('Login to enroll') }}</a>
                                    @elseif ($course->is_free || (float)$course->price == 0.0)
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="cf-button-primary w-full">{{ __('Get instant access') }}</button>
                                        </form>
                                    @elseif ($hasAnyPaymentMethod)
                                        @if ($hasAnyAvailablePaymentMethod)
                                            @if ($stripeAvailable)
                                                <form action="{{ route('payments.checkout', $course) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="cf-button-primary w-full">{{ __('Pay securely with Card') }}</button>
                                                </form>
                                            @endif

                                            @if ($paypalAvailable)
                                                <form action="{{ route('payments.paypal.checkout', $course) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="cf-button-secondary w-full">{{ __('Checkout with PayPal') }}</button>
                                                </form>
                                                <div id="paypal-button-container" class="mt-3" data-course-id="{{ (int) $course->id }}"></div>
                                                @if ($paypalClientIdValue !== '')
                                                    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientIdValue }}&currency={{ $course->currency ?? 'USD' }}&intent=capture"></script>
                                                    <script>
                                                        (function () {
                                                            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                                                            var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
                                                            var container = document.getElementById('paypal-button-container');
                                                            var courseId = container ? parseInt(container.getAttribute('data-course-id') || '0', 10) : 0;
                                                            function callCreateOrder() {
                                                                return fetch('{{ route('payments.paypal.create_order') }}', {
                                                                    method: 'POST',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': csrf
                                                                    },
                                                                    body: JSON.stringify({ course_id: courseId })
                                                                }).then(function (r) { return r.json(); }).then(function (d) { return d.order_id; });
                                                            }
                                                            function callCapture(orderId) {
                                                                return fetch('{{ route('payments.paypal.capture') }}', {
                                                                    method: 'POST',
                                                                    headers: {
                                                                        'Content-Type': 'application/json',
                                                                        'X-CSRF-TOKEN': csrf
                                                                    },
                                                                    body: JSON.stringify({ order_id: orderId })
                                                                }).then(function (r) { return r.json(); });
                                                            }
                                                            if (window.paypal && container) {
                                                                var funding = [paypal.FUNDING.PAYPAL, paypal.FUNDING.CARD];
                                                                funding.forEach(function (source) {
                                                                    paypal.Buttons({
                                                                        fundingSource: source,
                                                                        createOrder: function () { return callCreateOrder(); },
                                                                        onApprove: function (data) {
                                                                            return callCapture(data.orderID).then(function () {
                                                                                container.innerHTML = '<div class="rounded-2xl border border-[var(--color-primary)] bg-[var(--color-primary)]/10 p-3 text-sm text-[var(--color-primary)]">{{ __('Payment successful. You are enrolled.') }}</div>';
                                                                                var forms = container.parentElement.querySelectorAll('form');
                                                                                forms.forEach(function (f) { f.style.display = 'none'; });
                                                                            });
                                                                        },
                                                                        onError: function () {}
                                                                    }).render('#paypal-button-container');
                                                                });
                                                            }
                                                        })();
                                                    </script>
                                                @endif
                                            @endif

                                            @if ($hasManualPayment)
                                                <form action="{{ route('payments.manual.start', $course) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="cf-button-secondary w-full">{{ __('Request manual payment') }}</button>
                                                </form>
                                            @endif

                                            @if ($hasSomeUnavailablePaymentMethod)
                                                <p class="text-center text-xs text-[var(--color-text-muted)]">{{ __('Some payment methods are currently unavailable.') }}</p>
                                            @endif
                                        @else
                                            <div class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.04)] px-4 py-3 text-center text-sm text-[var(--color-text-muted)]">{{ __('Online payments are temporarily unavailable. Please contact the instructor.') }}</div>
                                        @endif
                                    @else
                                        <div class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.04)] px-4 py-3 text-center text-sm text-[var(--color-text-muted)]">{{ __('Online payments are temporarily unavailable. Please contact the instructor.') }}</div>
                                    @endif
                                </div>

                                <div class="cf-divider pt-4 text-sm text-[var(--color-text-muted)]">
                                    <p>{{ __('One-time purchase, no monthly subscription, and instant access after enrollment.') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        <div class="cf-section-shell">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <span class="cf-kicker">{{ __('Curriculum') }}</span>
                    <h2 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Curriculum preview') }}</h2>
                </div>
                @auth
                    @if ($isEnrolled)
                        <span class="cf-chip">{{ __('Progress') }}: {{ $progressPercent }}%</span>
                    @endif
                @endauth
            </div>

            @if (!empty($lessonModules) && $lessonModules->count())
                <div class="mt-6 space-y-5">
                    @foreach ($lessonModules as $module)
                        <div class="rounded-[22px] border border-[rgba(15,23,42,0.08)] bg-white/90 p-4 sm:p-5">
                            <div class="mb-4 flex flex-col gap-2 border-b border-[rgba(15,23,42,0.08)] pb-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-text-muted)]">{{ __('Module :number', ['number' => $module->position]) }}</p>
                                    <h3 class="mt-1 text-lg font-semibold text-[var(--color-text-primary)]">{{ $module->title }}</h3>
                                    @if ($module->description)
                                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $module->description }}</p>
                                    @endif
                                </div>
                                <span class="cf-chip">{{ __(':count lessons', ['count' => $module->lessons->count()]) }}</span>
                            </div>

                            <div class="space-y-3">
                                @foreach ($module->lessons as $l)
                                    @php
                                        $lessonCompleted = (bool) ($l->is_completed ?? false);
                                        $lessonLocked = (bool) ($l->is_locked ?? true);
                                    @endphp

                                    @if ($lessonLocked)
                                        <div class="cf-panel-soft block rounded-[14px] border border-[rgba(11,11,11,0.08)] px-5 py-4 opacity-80">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex min-w-0 items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-sm font-semibold text-[var(--color-primary)]">
                                                        {{ $l->position }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-medium text-[var(--color-text-primary)]">{{ $l->title }}</p>
                                                    </div>
                                                </div>
                                                <span class="cf-badge-muted text-xs">{{ __('Locked') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('lessons.show', [$course, $l]) }}" class="cf-panel-soft block rounded-[14px] border border-[rgba(11,11,11,0.08)] px-5 py-4 transition hover:border-[var(--color-primary)] hover:bg-[rgba(245,184,0,0.08)]">
                                            <div class="flex items-center justify-between gap-4">
                                                <div class="flex min-w-0 items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-sm font-semibold text-[var(--color-primary)]">
                                                        {{ $l->position }}
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-medium text-[var(--color-text-primary)]">{{ $l->title }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 whitespace-nowrap">
                                                    @if ($lessonCompleted)
                                                        <span class="cf-badge text-xs">{{ __('Completed') }}</span>
                                                    @else
                                                        <span class="cf-badge-muted text-xs">{{ __('Ready') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-6 cf-panel-soft px-6 py-8 text-center">
                    <p class="font-medium text-[var(--color-text-primary)]">{{ __('Lessons will appear once the course is published.') }}</p>
                    <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Once published, the curriculum preview will appear here.') }}</p>
                </div>
            @endif
        </div>
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        <div class="space-y-6">
                <div class="cf-section-shell">
                    @if (!empty($course->description))
                        <div>
                            <h2 class="text-xl font-bold text-[var(--color-text-primary)]">{{ __('About this course') }}</h2>
                            <div class="cf-lesson-richtext mt-4 text-[var(--color-text-muted)]">
                                {!! $course->description !!}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="cf-panel px-6 py-6 sm:px-8">
                    <div class="grid gap-5 sm:grid-cols-[auto,1fr] sm:items-start">
                        @if (!empty($profileImage))
                            <img
                                src="{{ $profileImage }}"
                                alt="{{ $displayName }}"
                                class="h-20 w-20 rounded-[24px] object-cover ring-4 ring-[var(--color-primary)]/10"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ $profileImageFallback }}';"
                            >
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-lg font-semibold text-[var(--color-primary)]">
                                {{ Str::substr($displayName ?? '', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <span class="cf-kicker">{{ __('Instructor') }}</span>
                            <h2 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $displayName }}</h2>
                            @if (!empty($displayBio))
                                <p class="mt-4 text-sm leading-7 text-[var(--color-text-muted)]">{{ $displayBio }}</p>
                            @endif
                            <div class="mt-5">
                                <a href="{{ route('instructor.show') }}" class="cf-button-secondary !px-4 !py-2">
                                    {{ __('View instructor profile') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cf-panel-soft px-6 py-6">
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $isEnrolled ? __('Learning details') : __('Enrollment details') }}</p>
                    <ul class="cf-check-list mt-4">
                        <li>{{ $lessons->count() > 0 ? __(':count structured lessons', ['count' => $lessons->count()]) : __('Structured curriculum preview') }}</li>
                        <li>{{ $isEnrolled ? __('Saved progress while learning') : __('Instant access after enrollment') }}</li>
                        <li>{{ $isEnrolled ? __('Continue from your next unfinished lesson') : ($course->is_free || (float) $course->price == 0.0 ? __('Free enrollment') : __('One-time payment')) }}</li>
                        <li>{{ __('Protected access from one clean learning dashboard') }}</li>
                    </ul>
                </div>
        </div>
    </section>
</x-public-layout>
