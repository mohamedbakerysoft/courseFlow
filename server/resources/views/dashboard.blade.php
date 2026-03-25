<x-app-layout>
    <x-slot name="header">
        @if ($isInstructor)
            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div class="max-w-3xl">
                    <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Admin workspace') }}</p>
                    <h2 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                        {{ __('See what needs attention and jump straight into the work') }}
                    </h2>
                    <p class="cf-dark-copy mt-3 max-w-2xl text-sm leading-7">
                        {{ __('Track products, lessons, students, and payments from one focused dashboard built for daily admin work instead of decorative filler.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('dashboard.courses.create') }}" class="cf-button-primary">{{ __('Create course') }}</a>
                    <a href="{{ route('dashboard.finance.manual_payments') }}" class="cf-button-secondary">{{ __('Review manual payments') }}</a>
                </div>
            </div>
        @else
            <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                <div class="max-w-3xl">
                    <p class="cf-dark-muted text-sm font-semibold uppercase tracking-[0.24em]">{{ __('Student dashboard') }}</p>
                    <h2 class="cf-dark-title mt-3 text-3xl font-bold tracking-[-0.04em] sm:text-4xl">
                        {{ __('Your enrolled courses stay organized in one learner view') }}
                    </h2>
                    <p class="cf-dark-copy mt-3 max-w-2xl text-sm leading-7">
                        {{ __('Continue learning, return to your enrolled courses quickly, and browse the catalog whenever you are ready for another course or book.') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Browse Courses') }}</a>
                    <a href="{{ route('books.index') }}" class="cf-button-secondary">{{ __('Browse Books') }}</a>
                </div>
            </div>
        @endif
    </x-slot>

    <div class="space-y-8">
        <x-public.demo-notice />

        @if ($isInstructor)
            {{-- Unified Stats Strip --}}
            <section class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                @foreach ($stats as $stat)
                    @php
                        $statSvg = match($stat['label']) {
                            'Courses' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>',
                            'Books' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                            'Lessons' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                            'Students' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                            'Manual payments' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>',
                            'Draft items' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5l13.732-13.732z"/>',
                            default => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        };
                    @endphp
                    <article class="p-5 pb-6 rounded-2xl border border-[rgba(11,11,11,0.06)] bg-[#fdfdfd] dark:border-white/5 dark:bg-[#151515] hover:border-[var(--color-primary)] dark:hover:border-[var(--color-primary)] hover:shadow-lg transition-all duration-300">
                        <div class="w-9 h-9 rounded-full bg-[var(--color-primary)]/10 dark:bg-[var(--color-primary)]/20 text-[var(--color-primary)] flex items-center justify-center mb-4 ring-4 ring-white dark:ring-[#111111]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                {!! $statSvg !!}
                            </svg>
                        </div>
                        <p class="text-[0.68rem] font-bold uppercase tracking-[0.15em] text-[var(--color-text-muted)]">{{ __($stat['label']) }}</p>
                        <p class="mt-1.5 text-3xl font-bold tracking-tight text-[var(--color-text-primary)] leading-none">{{ $stat['value'] }}</p>
                    </article>
                @endforeach
            </section>

            {{-- Actionable Alerts --}}
            @if (count($attentionItems))
            <section class="mt-6">
                <div class="space-y-3">
                    @foreach ($attentionItems as $item)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-[#1a1305]">
                            <div class="flex items-start sm:items-center gap-4">
                                <div class="shrink-0 mt-1 sm:mt-0">
                                    <span class="inline-flex items-center justify-center p-2 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-500 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-amber-950 dark:text-amber-400">{{ __($item['title']) }} <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-amber-200/50 text-amber-800 dark:bg-amber-500/20 dark:text-amber-500">{{ __($item['badge']) }}</span></h4>
                                    <p class="text-[0.85rem] mt-0.5 text-amber-800 dark:text-amber-500/80">{{ __($item['description']) }}</p>
                                </div>
                            </div>
                            <div class="shrink-0 flex items-center self-start sm:self-auto">
                                <a href="{{ $item['url'] }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-600 text-white text-xs font-bold uppercase tracking-wide hover:bg-amber-700 transition shadow-sm dark:bg-amber-500 dark:text-amber-950 dark:hover:bg-amber-400">
                                    {{ __($item['action']) }} &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            <section class="grid gap-6 xl:grid-cols-[1.2fr,0.8fr] mt-8">
                <!-- App-like Workspaces Grid -->
                <div>
                    <div class="mb-4">
                        <span class="cf-kicker">{{ __('Workspace') }}</span>
                        <h3 class="mt-1 text-2xl font-bold tracking-[-0.03em] text-[var(--color-text-primary)]">{{ __('Quick access to core areas') }}</h3>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 min-[900px]:grid-cols-3">
                        @foreach ($quickLinks as $link)
                            @php
                                $svg = match($link['title']) {
                                    'Courses' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />',
                                    'Books' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />',
                                    'Lessons' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
                                    'Users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
                                    'Finance insights' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />',
                                    'Manual payments' => '<path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />',
                                    'Landing page controls' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />',
                                    'FAQs' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                    'Appearance' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />',
                                    'Settings' => '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />',
                                    'Menus' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />',
                                    default => '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />'
                                };
                            @endphp
                            <a href="{{ $link['url'] }}" class="group relative block p-5 rounded-2xl border border-[rgba(11,11,11,0.06)] bg-[#fdfdfd] dark:border-white/5 dark:bg-[#151515] hover:border-[var(--color-primary)] dark:hover:border-[var(--color-primary)] hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <span class="absolute right-4 top-4 text-[10px] uppercase font-bold tracking-wider text-[var(--color-text-muted)] group-hover:text-[var(--color-primary)] transition-colors opacity-60 group-hover:opacity-100">{{ __($link['meta']) }}</span>
                                <div class="w-11 h-11 rounded-xl bg-[#ecf0f1] dark:bg-white/[0.03] flex items-center justify-center text-[var(--color-secondary)] dark:text-white/80 mb-4 group-hover:scale-110 group-hover:bg-[var(--color-primary)] group-hover:text-black transition-all duration-300">
                                    <svg class="w-5 h-5 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        {!! $svg !!}
                                    </svg>
                                </div>
                                <h4 class="font-bold text-[var(--color-text-primary)] transition-colors text-sm sm:text-base">{{ __($link['title']) }}</h4>
                                <p class="mt-1.5 text-xs text-[var(--color-text-muted)] leading-relaxed opacity-90 group-hover:opacity-100 transition-opacity">{{ __($link['description']) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Consolidated Activity Feed -->
                <div>
                    <div class="mb-4">
                        <span class="cf-kicker">{{ __('Feed') }}</span>
                        <h3 class="mt-1 text-2xl font-bold tracking-[-0.03em] text-[var(--color-text-primary)]">{{ __('Updates & Activity') }}</h3>
                    </div>

                    <div class="border border-[rgba(11,11,11,0.06)] bg-[#fdfdfd] dark:border-white/5 dark:bg-[#151515] rounded-2xl p-6 lg:p-7 shadow-sm">
                        
                        @if(count($recentPaidPayments))
                            <div class="mb-8">
                                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--color-text-muted)] mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                    {{ __('Latest Sales') }}
                                </h4>
                                <ul class="space-y-4">
                                    @foreach(array_slice($recentPaidPayments, 0, 3) as $item)
                                    <li class="flex justify-between items-start text-sm group">
                                        <div class="pr-3">
                                            <p class="font-bold text-green-700 dark:text-green-400 group-hover:text-green-500 transition-colors">{{ $item['title'] }}</p>
                                            <p class="text-xs text-[var(--color-text-muted)] mt-0.5 line-clamp-1 block">{{ $item['description'] }}</p>
                                        </div>
                                        <span class="text-[10px] font-semibold text-[var(--color-text-muted)] shrink-0 pt-0.5">{{ $item['meta'] }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($recentStudents))
                            <div class="mb-8 border-t border-[rgba(11,11,11,0.06)] dark:border-white/10 pt-6">
                                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--color-text-muted)] mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[var(--color-primary)] opacity-80"></span>
                                    {{ __('New Learners') }}
                                </h4>
                                <ul class="space-y-3">
                                    @foreach(array_slice($recentStudents, 0, 3) as $item)
                                    <li class="flex justify-between items-center text-sm p-2 -mx-2 rounded-lg hover:bg-[rgba(11,11,11,0.03)] dark:hover:bg-white/5 transition-colors">
                                        <div class="flex items-center gap-3 w-full min-w-0">
                                            <div class="shrink-0 w-8 h-8 rounded-full bg-[var(--color-secondary)]/10 dark:bg-white/10 text-[var(--color-secondary)] dark:text-white flex items-center justify-center font-bold text-xs ring-2 ring-white dark:ring-[#111111]">
                                                {{ Str::substr($item['title'], 0, 1) }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-[var(--color-text-primary)] truncate">{{ $item['title'] }}</p>
                                                <p class="text-[10px] text-[var(--color-text-muted)] truncate">{{ $item['meta'] }}</p>
                                            </div>
                                            <a href="{{ $item['url'] }}" class="shrink-0 text-xs font-semibold text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition pr-1">
                                                {{ __('View') }} &rarr;
                                            </a>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(count($draftItems))
                            <div class="pt-6 border-t border-[rgba(11,11,11,0.06)] dark:border-white/10">
                                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--color-text-muted)] mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[var(--color-secondary)]/40 dark:bg-white/30"></span>
                                    {{ __('Unpublished Drafts') }}
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($draftItems as $item)
                                    <li>
                                        <a href="{{ $item['url'] }}" class="group block p-3 rounded-xl border border-dashed border-[rgba(11,11,11,0.15)] dark:border-white/20 hover:border-[var(--color-primary)] dark:hover:border-[var(--color-primary)] hover:bg-[var(--color-primary)]/5 transition-all outline-none">
                                            <div class="flex items-center justify-between">
                                                <p class="font-bold text-[var(--color-text-primary)] text-sm group-hover:text-[var(--color-primary)] truncate pr-4">{{ $item['title'] }}</p>
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded border border-[var(--color-secondary)]/20 dark:border-white/20 text-[var(--color-secondary)] dark:text-white uppercase tracking-wider shrink-0">{{ $item['badge'] }}</span>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(!count($recentPaidPayments) && !count($recentStudents) && !count($draftItems))
                            <div class="py-12 text-center text-[var(--color-text-muted)] flex flex-col items-center">
                                <svg class="w-12 h-12 mb-4 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-sm font-medium">{{ __('No recent activity to display.') }}</p>
                                <p class="text-xs mt-1">{{ __('Publish a course to get started.') }}</p>
                            </div>
                        @endif

                    </div>
                </div>
            </section>
        @else
            <section class="cf-section-shell">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <span class="cf-kicker">{{ __('Student dashboard') }}</span>
                        <h3 class="mt-3 text-2xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('Your enrolled courses stay organized in one learner view') }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ __('Browse what you have enrolled in, continue lessons, and return to the catalog when you are ready for more.') }}</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="cf-button-primary">{{ __('Browse Courses') }}</a>
                </div>

                @if (!empty($enrolledCourses) && $enrolledCourses->count())
                    <div class="mt-8 cf-card-grid">
                        @foreach ($enrolledCourses as $course)
                            <x-course.card :course="$course" ctaLabel="Continue" />
                        @endforeach
                    </div>
                @else
                    <div class="mt-8 cf-panel-soft px-8 py-10 text-center">
                        <p class="text-2xl font-bold text-[var(--color-text-primary)]">{{ __('No enrollments yet') }}</p>
                        <p class="mt-3 text-sm text-[var(--color-text-muted)]">{{ __('Browse courses to start building your learning library.') }}</p>
                    </div>
                @endif
            </section>
        @endif
    </div>
</x-app-layout>
