<x-public-layout :title="$course->title" :metaDescription="str($course->description)->limit(160)">
    @php
        $isArabic = app()->getLocale() === 'ar';
        $displayPrice = $course->is_free || (float) $course->price == 0.0
            ? __('Free')
            : number_format((float) $course->price, 2).' '.$course->currency;
        $displayName = $instructorName !== '' ? $instructorName : ($course->instructor->name ?? '');
        $displayBio = $instructorBio !== '' ? $instructorBio : ($course->instructor->bio ?? '');
        $profileImage = !empty($instructorImageUrl)
            ? $instructorImageUrl
            : ($course->instructor?->profile_image_url ?? \App\Support\MediaAsset::avatarFallback($displayName));
        $profileImageFallback = $course->instructor?->profile_image_fallback_url ?? \App\Support\MediaAsset::avatarFallback($displayName);
        $thumbnail = $course->thumbnail_url;
        $thumbnailFallback = $course->thumbnail_fallback_url;
        $courseSignal = Str::lower($course->slug.' '.$course->title.' '.$course->description);
        $courseBlueprint = match (true) {
            Str::contains($courseSignal, ['arabic', 'rtl', 'localization']) => [
                'outcomes' => [
                    __('Build an Arabic-ready learner experience with clearer RTL structure.'),
                    __('Translate the storefront and course flow without losing clarity.'),
                    __('Ship a localized experience that still feels clear and easy to navigate.'),
                ],
                'audience' => [
                    __('Creators serving Arabic-speaking students.'),
                    __('Teams adapting an English course business to RTL.'),
                    __('Instructors improving bilingual clarity and trust.'),
                ],
                'requirements' => [
                    __('Basic familiarity with CourseFlow or Laravel project setup.'),
                    __('A clear idea of the audience you want to localize for.'),
                    __('Willingness to test layout, content, and lesson flow carefully.'),
                ],
                'faq' => [
                    [
                        'question' => __('Will this help with both translation and layout?'),
                        'answer' => __('Yes. The lessons focus on language adaptation, RTL layout, and a smoother learner experience together.'),
                    ],
                    [
                        'question' => __('Is it suitable for an existing course business?'),
                        'answer' => __('Yes. It works well for creators expanding into Arabic without rebuilding their whole storefront.'),
                    ],
                    [
                        'question' => __('Do I need advanced coding experience?'),
                        'answer' => __('Intermediate familiarity is enough if you can follow structured lessons and test each change carefully.'),
                    ],
                ],
            ],
            Str::contains($courseSignal, ['launch', 'marketing', 'sales']) => [
                'outcomes' => [
                    __('Clarify the offer, positioning, and sales story behind your course.'),
                    __('Prepare a smoother launch path from landing page to checkout.'),
                    __('Turn course ideas into a more focused enrollment campaign.'),
                ],
                'audience' => [
                    __('Creators planning a new paid course launch.'),
                    __('Instructors improving weak positioning or unclear sales pages.'),
                    __('Course businesses that want a simpler path to purchase.'),
                ],
                'requirements' => [
                    __('A course topic, outline, or offer you want to launch.'),
                    __('Basic understanding of your target student.'),
                    __('Willingness to revise messaging, pricing, and launch assets.'),
                ],
                'faq' => [
                    [
                        'question' => __('Is this focused on strategy or setup?'),
                        'answer' => __('It combines positioning, offer clarity, and practical steps that support a clearer sales journey.'),
                    ],
                    [
                        'question' => __('Will this help if I already have a course?'),
                        'answer' => __('Yes. It is useful both for fresh launches and for improving an existing course offer.'),
                    ],
                    [
                        'question' => __('Do I need a large audience first?'),
                        'answer' => __('No. The goal is to help you present and sell more clearly, even with a small but relevant audience.'),
                    ],
                ],
            ],
            default => [
                'outcomes' => [
                    __('Move through the topic with a clearer lesson sequence and practical next steps.'),
                    __('Understand how the course connects to real implementation, not just theory.'),
                    __('Finish with a repeatable workflow you can apply in your own projects.'),
                ],
                'audience' => [
                    __('Beginners who want a guided path instead of scattered resources.'),
                    __('Creators looking for a clearer way to learn and apply the topic.'),
                    __('Students who value structure, clarity, and faster confidence.'),
                ],
                'requirements' => [
                    __('A laptop, internet connection, and time to work through the lessons.'),
                    __('Willingness to learn step by step and follow the curriculum in order.'),
                    __('Curiosity to test ideas inside your own project or workflow.'),
                ],
                'faq' => [
                    [
                        'question' => __('Can I take this course at my own pace?'),
                        'answer' => __('Yes. The course is designed for self-paced learning, with lesson order and progress tracking built in.'),
                    ],
                    [
                        'question' => __('What happens after I enroll?'),
                        'answer' => __('You get immediate access to the available lessons and can continue from where you left off later.'),
                    ],
                    [
                        'question' => __('Is this suitable for first-time learners?'),
                        'answer' => __('Yes. The storefront and curriculum are designed to help new learners feel oriented from the start.'),
                    ],
                ],
            ],
        };
        $courseIncludes = [
            $lessons->count() > 0
                ? __(':count structured lessons', ['count' => $lessons->count()])
                : __('Structured curriculum preview'),
            $course->is_free || (float) $course->price == 0.0 ? __('Free enrollment') : __('One-time payment'),
            __('Instant access after enrollment'),
            __('Saved progress while learning'),
        ];
    @endphp

    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Courses'), 'url' => route('courses.index')],
            ['label' => $course->title],
        ]" />

        <div class="mt-6 space-y-6">
            <x-public.demo-notice />

            <div class="grid gap-6 lg:grid-cols-[1.08fr,0.92fr] lg:items-center">
                <div class="space-y-5">
                    <a href="{{ route('courses.index') }}" class="cf-button-ghost !px-4 !py-2">{{ __('Back to courses') }}</a>
                    <div class="flex flex-wrap gap-2">
                        <span class="cf-chip">{{ $displayPrice }}</span>
                        <span class="cf-chip">{{ $lessons->count() }} {{ $isArabic ? 'دروس' : Str::plural('lessons', $lessons->count()) }}</span>
                        <span class="cf-chip">{{ strtoupper($course->language) }}</span>
                    </div>
                    <div class="space-y-4">
                        <h1 class="cf-display text-4xl sm:text-5xl">{{ $course->title }}</h1>
                        @if (!empty($course->description))
                            <p class="cf-subheading max-w-3xl">{{ str($course->description)->limit(220) }}</p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="cf-panel-soft px-4 py-3">
                            <p class="font-semibold text-[var(--color-text-primary)]">{{ $course->instructor?->name ?? $instructorName }}</p>
                            <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Instructor') }}</p>
                        </div>
                        <div class="cf-panel-soft px-4 py-3">
                            <p class="font-semibold text-[var(--color-text-primary)]">{{ __('Self-paced format') }}</p>
                            <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Learn through a focused curriculum at your own pace') }}</p>
                        </div>
                        <div class="cf-panel-soft px-4 py-3">
                            <p class="font-semibold text-[var(--color-text-primary)]">{{ __('Enrollment ready') }}</p>
                            <p class="mt-1 text-[var(--color-text-muted)]">{{ __('Card, PayPal, or manual payment support') }}</p>
                        </div>
                    </div>
                    @auth
                        @if ($isEnrolled)
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                                {{ __('You are enrolled') }}. {{ __('Progress') }}: {{ $progressPercent }}%
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="cf-panel overflow-hidden">
                    <div class="relative">
                        <img
                            src="{{ $thumbnail }}"
                            alt="{{ $course->title }}"
                            class="aspect-[16/11] w-full object-cover"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='{{ $thumbnailFallback }}';"
                        >
                        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(180deg,rgba(7,10,20,0)_35%,rgba(7,10,20,0.18)_100%)]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cf-shell pb-8">
        <x-public.trust-bar />
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        <div class="grid gap-8 lg:grid-cols-[1.12fr,0.88fr]">
            <div class="space-y-6">
                <div class="cf-panel px-6 py-6 sm:px-8">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <span class="cf-kicker">{{ __('What you will learn') }}</span>
                            <ul class="cf-check-list mt-5">
                                @foreach ($courseBlueprint['outcomes'] as $outcome)
                                    <li>{{ $outcome }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="cf-panel-soft px-5 py-5">
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[var(--color-text-muted)]">{{ __('This course includes') }}</p>
                            <ul class="cf-check-list mt-4">
                                @foreach ($courseIncludes as $courseInclude)
                                    <li>{{ $courseInclude }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if (!empty($course->description))
                        <div class="cf-divider mt-6 pt-6">
                            <h2 class="text-xl font-bold text-[var(--color-text-primary)]">{{ __('About this course') }}</h2>
                            <div class="mt-4 text-sm leading-8 text-[var(--color-text-muted)]">
                                {!! nl2br(e($course->description)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <article class="cf-panel px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Who this is for') }}</p>
                        <ul class="cf-check-list mt-4">
                            @foreach ($courseBlueprint['audience'] as $audience)
                                <li>{{ $audience }}</li>
                            @endforeach
                        </ul>
                    </article>
                    <article class="cf-panel px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Requirements') }}</p>
                        <ul class="cf-check-list mt-4">
                            @foreach ($courseBlueprint['requirements'] as $requirement)
                                <li>{{ $requirement }}</li>
                            @endforeach
                        </ul>
                    </article>
                    <article class="cf-panel px-5 py-5">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('What to expect') }}</p>
                        <ul class="cf-check-list mt-4">
                            <li>{{ __('A clear lesson path instead of scattered resources.') }}</li>
                            <li>{{ __('A clear decision journey from course page to enrollment.') }}</li>
                            <li>{{ __('A storefront that keeps the instructor visible and credible.') }}</li>
                        </ul>
                    </article>
                </div>

                <div class="cf-panel px-6 py-6 sm:px-8">
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

                    @if (!empty($lessons) && $lessons->count())
                        <div class="mt-6 space-y-3">
                            @foreach ($lessons as $l)
                                <div class="cf-panel-soft flex items-center justify-between gap-4 px-5 py-4">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-sm font-semibold text-[var(--color-primary)]">
                                            {{ $l->position }}
                                        </div>
                                        @auth
                                            @if ($isEnrolled)
                                                <a href="{{ route('lessons.show', [$course, $l]) }}" class="truncate text-sm font-medium text-[var(--color-text-primary)] hover:text-[var(--color-primary)]">
                                                    {{ $l->title }}
                                                </a>
                                            @else
                                                <span class="truncate text-sm text-[var(--color-text-muted)]">{{ $l->title }}</span>
                                            @endif
                                        @else
                                            <span class="truncate text-sm text-[var(--color-text-muted)]">{{ $l->title }}</span>
                                        @endauth
                                    </div>
                                    <span class="text-xs font-medium uppercase tracking-[0.18em] text-[var(--color-text-muted)]">
                                        @auth
                                            {{ $isEnrolled ? __('Open') : __('Locked') }}
                                        @else
                                            {{ __('Locked') }}
                                        @endauth
                                    </span>
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
            </div>

            <div class="space-y-6">
                <div class="cf-panel sticky top-28 px-6 py-6 sm:px-8">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $course->title }}</p>
                                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Review the curriculum, choose a payment method, and start learning right away.') }}</p>
                            </div>
<<<<<<< HEAD
                            <div class="text-right">
                                <p class="text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ $displayPrice }}</p>
                                <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $lessons->count() }} {{ $isArabic ? 'دروس' : Str::plural('lessons', $lessons->count()) }}</p>
                            </div>
                        </div>

                        <div class="grid gap-2">
                            @guest
                                <a href="{{ route('login') }}" class="cf-button-primary w-full">{{ __('Login to enroll') }}</a>
=======
                            <div class="flex items-baseline justify-end gap-2 text-right">
                                @if ($course->is_free || (float)$course->price == 0.0)
                                    <span class="text-lg font-semibold text-[var(--color-primary)] whitespace-nowrap">
                                        {{ __('Free') }}
                                    </span>
                                @else
                                    <span class="text-lg font-semibold text-[var(--color-text-primary)] whitespace-nowrap">
                                        {{ number_format((float)$course->price, 2) }}
                                    </span>
                                    <span class="text-sm font-semibold text-[var(--color-text-primary)] whitespace-nowrap">
                                        {{ strtoupper($course->currency) }}
                                    </span>
                                @endif
                                <span class="text-xs text-[var(--color-text-muted)] whitespace-nowrap">
                                    {{ $lessons->count() }} {{ ($appLocale ?? app()->getLocale()) === 'ar' ? 'دروس' : Str::plural(__('lesson'), $lessons->count()) }}
                                </span>
                            </div>
                        </div>
                        @php $ar = app()->getLocale() === 'ar'; @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                            <div class="inline-flex items-center justify-center h-10 rounded-full border border-[var(--color-secondary)]/25 bg-white px-3 text-xs font-medium text-[var(--color-text-muted)] whitespace-nowrap min-w-[160px]">
                                {{ $ar ? 'الدفع عبر سترايب وباي بال' : 'Payments handled via Stripe & PayPal' }}
                            </div>
                            <div class="inline-flex items-center justify-center h-10 rounded-full border border-[var(--color-secondary)]/25 bg-white px-3 text-xs font-medium text-[var(--color-text-muted)] whitespace-nowrap min-w-[160px]">
                                {{ $ar ? 'وصول فوري بعد التسجيل' : 'Instant access after enrollment' }}
                            </div>
                            <div class="inline-flex items-center justify-center h-10 rounded-full border border-[var(--color-secondary)]/25 bg-white px-3 text-xs font-medium text-[var(--color-text-muted)] whitespace-nowrap min-w-[160px]">
                                {{ $ar ? 'دفع لمرة واحدة (بدون اشتراك)' : 'One‑time payment (no subscription)' }}
                            </div>
                            <div class="inline-flex items-center justify-center h-10 rounded-full border border-[var(--color-secondary)]/25 bg-white px-3 text-xs font-medium text-[var(--color-text-muted)] whitespace-nowrap min-w-[160px]">
                                {{ $ar ? 'تركيز على الخصوصية' : 'Privacy‑focused' }}
                            </div>
                        </div>
                        <div class="space-y-4">
                @guest
                    <a href="{{ route('login') }}" class="inline-flex w-full sm:w-auto mx-auto justify-center items-center px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                        {{ __('Login to Enroll') }}
                    </a>
>>>>>>> 9bd7396 (feat(courses): improve course details layout and payment info display)
                            @else
                                @if ($isEnrolled)
                                    @if (!empty($firstLesson))
                                        <a href="{{ route('lessons.show', [$course, $firstLesson]) }}" class="cf-button-primary w-full">{{ __('Continue learning') }}</a>
                                    @endif
                                    <p class="text-center text-sm text-[var(--color-text-muted)]">{{ __('You are already enrolled in this course.') }}</p>
                                @else
                                    @if ($course->is_free || (float)$course->price == 0.0)
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="cf-button-primary w-full">{{ __('Get instant access') }}</button>
                                        </form>
                                    @else
                                        @if ($hasAnyPaymentMethod)
                                            @php
                                                $envOk = app()->environment(['production']) ? true : false;
                                                $stripeConfigValid = !$envOk || ((string) config('services.stripe.publishable_key') !== '' && (string) config('services.stripe.secret') !== '');
                                                $settingsSvc = app(\App\Services\SettingsService::class);
                                                $paypalClientIdVal = (string) $settingsSvc->get('paypal.client_id', '');
                                                $paypalSecretVal = (string) $settingsSvc->get('paypal.client_secret', '');
                                                $paypalModeVal = (string) $settingsSvc->get('paypal.mode', 'sandbox');
                                                $paypalClientOk = $paypalClientIdVal !== '' && $paypalSecretVal !== '';
                                                $paypalModeOk = in_array(strtolower($paypalModeVal), ['sandbox', 'live'], true);
                                                $paypalConfigValid = !$envOk || ($paypalClientOk && $paypalModeOk);
                                                $stripeAvailable = $isStripeEnabled && $stripeConfigValid;
                                                $paypalAvailable = $isPayPalEnabled && $paypalConfigValid;
                                                $hasAnyAvailable = $stripeAvailable || $paypalAvailable || $hasManualPayment;
                                                $hasSomeUnavailable = ($isStripeEnabled && ! $stripeAvailable) || ($isPayPalEnabled && ! $paypalAvailable);
                                            @endphp

                                            @if ($hasAnyAvailable)
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
                                                    @php
                                                        $ppClientId = (string) app(\App\Services\SettingsService::class)->get('paypal.client_id', '');
                                                        $ppCurrency = $course->currency ?? 'USD';
                                                    @endphp
                                                    @if ($ppClientId !== '')
                                                        <script src="https://www.paypal.com/sdk/js?client-id={{ $ppClientId }}&currency={{ $ppCurrency }}&intent=capture"></script>
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
                                                                                    container.innerHTML = '<div class="rounded-2xl border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ __('Payment successful. You are enrolled.') }}</div>';
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

                                                @if ($hasSomeUnavailable)
                                                    <p class="text-center text-xs text-[var(--color-text-muted)]">{{ __('Some payment methods are currently unavailable.') }}</p>
                                                @endif
                                            @else
                                                <div class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.04)] px-4 py-3 text-center text-sm text-[var(--color-text-muted)]">
                                                    {{ __('Online payments are temporarily unavailable. Please contact the instructor.') }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="rounded-2xl border border-[rgba(15,23,42,0.08)] bg-[rgba(15,23,42,0.04)] px-4 py-3 text-center text-sm text-[var(--color-text-muted)]">
                                                {{ __('Online payments are temporarily unavailable. Please contact the instructor.') }}
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endguest
                        </div>

                        <div class="cf-divider pt-4 text-sm text-[var(--color-text-muted)]">
                            <p>{{ __('One-time purchase, no monthly subscription, and instant access after enrollment.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="cf-panel-soft px-6 py-6">
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Enrollment details') }}</p>
                    <ul class="cf-check-list mt-4">
                        @foreach ($courseIncludes as $courseInclude)
                            <li>{{ $courseInclude }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="cf-panel-soft px-6 py-6">
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Quick answers') }}</p>
                    <div class="mt-4 space-y-4">
                        @foreach ($courseBlueprint['faq'] as $faqItem)
                            <div class="rounded-[22px] border border-[rgba(17,17,19,0.08)] px-4 py-4">
                                <p class="font-medium text-[var(--color-text-primary)]">{{ $faqItem['question'] }}</p>
                                <p class="mt-2 text-sm leading-7 text-[var(--color-text-muted)]">{{ $faqItem['answer'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
