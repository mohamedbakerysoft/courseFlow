<x-public-layout :title="$heroTitle" :metaDescription="$heroSubtitle">
    <div class="bg-white">
        <div class="max-w-6xl mx-auto px-6 sm:px-8 lg:px-10 space-y-20 lg:space-y-24 py-10 lg:py-16">
            <section id="hero" class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[var(--color-primary)]/10 via-white to-[var(--color-primary)]/5 px-6 sm:px-10 py-10 sm:py-14 lg:py-16">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute -right-24 -top-24 h-64 w-64 sm:h-80 sm:w-80 rounded-full bg-[var(--color-primary)]/10 blur-3xl"></div>
                    <div class="absolute -left-16 -bottom-32 h-64 w-64 rounded-full bg-[var(--color-primary)]/5 blur-3xl"></div>
                </div>
                <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
                    <div class="space-y-6">
                        <p class="text-xs sm:text-sm font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                            {{ __('Sales Coach & Consultant') }}
                        </p>
                        <div class="space-y-4">
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900">
                                {{ $heroTitle }}
                            </h1>
                            <p class="text-base sm:text-lg text-gray-600 max-w-xl">
                                {{ $heroSubtitle }}
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-3">
                            <a href="{{ route('courses.index') }}"
                               class="inline-flex justify-center items-center w-full sm:w-auto px-7 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                {{ __('Browse Courses') }}
                            </a>
                            <a href="#contact"
                               class="inline-flex justify-center items-center w-full sm:w-auto px-6 py-3 rounded-full border border-gray-200 bg-white text-sm font-medium text-gray-800 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]/40">
                                {{ __('Book a free discovery call') }}
                            </a>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-[var(--color-primary)]"></span>
                                <span>{{ __('Proven frameworks used by sales teams and founders') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-[var(--color-primary)]/70"></span>
                                <span>{{ __('Actionable templates, scripts, and real call breakdowns') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex justify-center lg:justify-end">
                        <div class="relative h-64 w-64 sm:h-72 sm:w-72 lg:h-80 lg:w-80">
                            <div class="absolute inset-0 rounded-full bg-[var(--color-primary)]/10"></div>
                            <div class="absolute inset-4 rounded-full bg-white shadow-xl ring-1 ring-gray-100 overflow-hidden">
                                <img
                                    src="{{ $instructorImageUrl ?? asset('images/demo/IMG_1702.JPG') }}"
                                    alt="{{ __('Instructor hero') }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-label="{{ __('Featured courses') }}" class="space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                            {{ __('Courses') }}
                        </p>
                        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                            {{ __('Featured courses') }}
                        </h2>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-semibold text-[var(--color-primary)] hover:underline">
                        {{ __('View all courses') }}
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($featuredCourses as $course)
                        <x-course.card :course="$course" />
                    @empty
                        <p class="text-sm text-gray-600">{{ __('No courses available yet') }}</p>
                    @endforelse
                </div>
            </section>

            <section aria-label="{{ __('About Mena Mahmoud') }}" class="space-y-10">
                <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)] gap-10 items-center">
                    <div class="space-y-4">
                        <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                            {{ __('Meet your coach') }}
                        </p>
                        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                            {{ $instructor?->name ?? __('Your Instructor') }}
                        </h2>
                        @if (!empty($instructor?->bio))
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                {{ $instructor->bio }}
                            </p>
                        @endif
                        @if (!empty($instructorLinks))
                            <div class="flex gap-3 text-sm">
                                @foreach ($instructorLinks as $label => $url)
                                    <a href="{{ $url }}" class="text-[var(--color-primary)] hover:underline" rel="noopener" target="_blank">{{ ucfirst($label) }}</a>
                                @endforeach
                            </div>
                        @endif
                        <div class="flex flex-wrap gap-3 text-xs sm:text-sm">
                            <div class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-gray-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-primary)]"></span>
                                <span>{{ __('Sales career roadmaps') }}</span>
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-gray-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-primary)]"></span>
                                <span>{{ __('Live coaching & feedback') }}</span>
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-gray-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--color-primary)]"></span>
                                <span>{{ __('Interview & presentation prep') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center lg:justify-end">
                        <div class="overflow-hidden rounded-3xl shadow-xl ring-1 ring-gray-100 max-w-xs w-full">
                            <img
                                src="{{ asset('images/demo/IMG_1700.JPG') }}"
                                alt="{{ __('Portrait of Mena Mahmoud') }}"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            >
                        </div>
                    </div>
                </div>
            </section>

            <section aria-label="{{ __('How I can help you') }}" class="space-y-8">
                <div class="space-y-3 text-center">
                    <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                        {{ __('Work with a clear plan') }}
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ __('Choose the support that fits your next step') }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto">
                        {{ __('Whether you are just starting or already closing deals, you will find a focused path to upgrade your skills and confidence.') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 flex flex-col gap-3">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                            <span class="text-sm font-semibold">01</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ __('Sales Fundamentals Lab') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ __('Master the basics of prospecting, discovery calls, and objection handling with simple scripts and checklists.') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-primary)]/20 p-6 flex flex-col gap-3">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-primary)] text-white">
                            <span class="text-sm font-semibold">02</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ __('Career & Interview Coaching') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ __('Position yourself for sales roles, practice interviews, and tell your story in a confident, structured way.') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 flex flex-col gap-3">
                        <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                            <span class="text-sm font-semibold">03</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ __('1:1 Strategy Sessions') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ __('Bring your real deals, offers, or presentations and leave with a clear action plan for the next 30 days.') }}
                        </p>
                    </div>
                </div>
            </section>

            <section aria-label="{{ __('Featured programs') }}" class="space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                            {{ __('Programs & courses') }}
                        </p>
                        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                            {{ __('Start with a clear, focused program') }}
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600 max-w-xl">
                            {{ __('Each program is designed to be practical, compact, and easy to apply around your work schedule.') }}
                        </p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-semibold text-[var(--color-primary)] hover:underline">
                        {{ __('View all courses') }}
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <article class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden flex flex-col">
                        <div class="h-40 bg-gray-50 overflow-hidden">
                            <img
                                src="{{ asset('images/demo/course-1.svg') }}"
                                alt="{{ __('Sales fundamentals course illustration') }}"
                                class="w-full h-full object-contain"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-5 flex flex-col flex-1 gap-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-[var(--color-primary)]/80">
                                {{ __('Foundations') }}
                            </p>
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ __('Sales Fundamentals: From First Call to First Deal') }}
                            </h3>
                            <p class="text-sm text-gray-600 flex-1">
                                {{ __('Build a solid base in prospecting, discovery, and closing so you can start selling with confidence.') }}
                            </p>
                            <div class="flex items-center justify-between text-xs mt-1">
                                <span class="font-semibold text-gray-900">{{ __('Beginner friendly') }}</span>
                                <span class="rounded-full bg-green-50 px-2.5 py-1 text-[11px] font-medium text-green-700">
                                    {{ __('Self-paced') }}
                                </span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-[var(--color-primary)]">
                                    {{ __('Coming soon') }}
                                </span>
                                <a href="{{ route('courses.index') }}" class="text-xs font-semibold text-[var(--color-primary)] hover:underline">
                                    {{ __('Learn more') }}
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden flex flex-col">
                        <div class="h-40 bg-gray-50 overflow-hidden">
                            <img
                                src="{{ asset('images/demo/course-2.svg') }}"
                                alt="{{ __('Interview coaching course illustration') }}"
                                class="w-full h-full object-contain"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-5 flex flex-col flex-1 gap-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-[var(--color-primary)]/80">
                                {{ __('Career') }}
                            </p>
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ __('Sales Interview & Role-Play Bootcamp') }}
                            </h3>
                            <p class="text-sm text-gray-600 flex-1">
                                {{ __('Practice real interview scenarios, structure your answers, and show up as the obvious choice for the role.') }}
                            </p>
                            <div class="flex items-center justify-between text-xs mt-1">
                                <span class="font-semibold text-gray-900">{{ __('Job seekers') }}</span>
                                <span class="rounded-full bg-purple-50 px-2.5 py-1 text-[11px] font-medium text-[var(--color-primary)]">
                                    {{ __('Live cohort') }}
                                </span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-[var(--color-primary)]">
                                    {{ __('Waitlist open') }}
                                </span>
                                <a href="{{ route('courses.index') }}" class="text-xs font-semibold text-[var(--color-primary)] hover:underline">
                                    {{ __('Join waitlist') }}
                                </a>
                            </div>
                        </div>
                    </article>

                    <article class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 overflow-hidden flex flex-col">
                        <div class="h-40 bg-gray-50 overflow-hidden">
                            <img
                                src="{{ asset('images/demo/course-3.svg') }}"
                                alt="{{ __('Personal brand course illustration') }}"
                                class="w-full h-full object-contain"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-5 flex flex-col flex-1 gap-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-[var(--color-primary)]/80">
                                {{ __('Brand') }}
                            </p>
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ __('Personal Brand for Sales Professionals') }}
                            </h3>
                            <p class="text-sm text-gray-600 flex-1">
                                {{ __('Create a clear positioning, LinkedIn profile, and content plan that brings opportunities to you.') }}
                            </p>
                            <div class="flex items-center justify-between text-xs mt-1">
                                <span class="font-semibold text-gray-900">{{ __('Intermediate') }}</span>
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700">
                                    {{ __('Templates included') }}
                                </span>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-[var(--color-primary)]">
                                    {{ __('Coming soon') }}
                                </span>
                                <a href="{{ route('courses.index') }}" class="text-xs font-semibold text-[var(--color-primary)] hover:underline">
                                    {{ __('Learn more') }}
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <section aria-label="{{ __('Testimonials') }}" class="space-y-8">
                <div class="space-y-3 text-center">
                    <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                        {{ __('Student results') }}
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ __('What people say after working together') }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto">
                        {{ __('These are examples of the type of transformations and clarity you can expect when you commit to the work.') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 flex flex-col gap-3">
                        <p class="text-sm text-gray-700">
                            {{ __('"I went from avoiding sales calls to actually looking forward to them. The scripts and mindset shifts changed everything for me."') }}
                        </p>
                        <p class="text-xs font-semibold text-gray-900">
                            {{ __('Sara, Freelance designer') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 flex flex-col gap-3">
                        <p class="text-sm text-gray-700">
                            {{ __('"After our interview prep, I received two offers in the same week. I finally knew how to talk about my experience clearly."') }}
                        </p>
                        <p class="text-xs font-semibold text-gray-900">
                            {{ __('Omar, Sales representative') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6 flex flex-col gap-3">
                        <p class="text-sm text-gray-700">
                            {{ __('"The frameworks are simple but powerful. I used them with my team and we closed our biggest month so far."') }}
                        </p>
                        <p class="text-xs font-semibold text-gray-900">
                            {{ __('Laila, Startup founder') }}
                        </p>
                    </div>
                </div>
            </section>

            <section id="contact" aria-label="{{ __('Contact form') }}" class="space-y-8">
                <div class="space-y-3 text-center">
                    <p class="text-xs font-semibold text-[var(--color-primary)]/80 tracking-wide uppercase">
                        {{ __('Get in touch') }}
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900">
                        {{ __('Tell me about your goals') }}
                    </h2>
                    <p class="text-sm sm:text-base text-gray-600 max-w-xl mx-auto">
                        {{ __('Share a few details about where you are right now and what you want to achieve. I will get back to you with the best next step.') }}
                    </p>
                </div>
                <div class="bg-white rounded-3xl shadow-sm ring-1 ring-gray-100 p-6 sm:p-8 lg:p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)] gap-8">
                        <form class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        {{ __('Full name') }}
                                    </label>
                                    <input type="text" class="block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder=""/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        {{ __('Email') }}
                                    </label>
                                    <input type="email" class="block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder=""/>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        {{ __('Current role') }}
                                    </label>
                                    <input type="text" class="block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder=""/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        {{ __('Main goal') }}
                                    </label>
                                    <input type="text" class="block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder=""/>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    {{ __('What would you like help with?') }}
                                </label>
                                <textarea rows="4" class="block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]" placeholder=""></textarea>
                            </div>
                            <div class="pt-2">
                                <button type="button" class="inline-flex justify-center items-center px-6 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ __('Submit request') }}
                                </button>
                            </div>
                        </form>
                        <div class="space-y-4 text-sm text-gray-600">
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ __('What happens after you send this?') }}
                            </h3>
                            <p>
                                {{ __('You will receive a short email with a suggested next step: a course, a live program, or a 1:1 session, depending on what fits you best.') }}
                            </p>
                            <p>
                                {{ __('You are not committing to anything by sending this form. It is simply a way to get personalized guidance instead of guessing your next move.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <section aria-label="{{ __('Final call to action') }}">
                <div class="rounded-3xl bg-[var(--color-primary)] text-white px-6 sm:px-10 py-10 lg:py-12 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-2">
                        <h2 class="text-2xl sm:text-3xl font-semibold">
                            {{ __('Ready to take your next sales step?') }}
                        </h2>
                        <p class="text-sm sm:text-base text-white/80 max-w-xl">
                            {{ __('Start with one focused program, apply the frameworks, and watch your confidence and results grow with every conversation.') }}
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row flex-wrap items-center gap-3 w-full md:w-auto">
                        <a href="{{ route('courses.index') }}"
                           class="inline-flex justify-center items-center w-full sm:w-auto px-7 py-3 rounded-full bg-white text-sm font-semibold text-[var(--color-primary)] shadow-sm hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                            {{ __('Browse courses') }}
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-public-layout>
