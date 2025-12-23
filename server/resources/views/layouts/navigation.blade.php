<nav x-data="{ open: false }" class="cf-nav">
    <div class="cf-shell">
        <div class="flex h-20 items-center justify-between gap-4">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    @if (!empty($siteLogoUrl))
                        <img src="{{ $siteLogoUrl }}" alt="{{ config('app.name') }}" class="h-11 w-auto rounded-2xl">
                    @else
                        <div class="cf-nav-logo">
                            <x-branding-logo class="h-6 w-6 fill-current text-white" />
                        </div>
                    @endif
                    <div class="hidden sm:block">
                        <p class="cf-nav-brand-eyebrow text-sm font-semibold uppercase tracking-[0.22em]">{{ config('app.name') }}</p>
                        <p class="text-sm text-[var(--color-text-primary)]">{{ __('Course storefront') }}</p>
                    </div>
                </a>

                <div class="hidden items-center gap-2 lg:flex">
                    @guest
                        <x-nav-link :href="url('/')" :active="request()->is('/')">{{ __('Home') }}</x-nav-link>
                        <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index') || request()->routeIs('courses.show')">{{ __('Courses') }}</x-nav-link>
                        <x-nav-link :href="route('instructor.show')" :active="request()->routeIs('instructor.show')">{{ __('Instructor') }}</x-nav-link>
                    @endguest
                    @auth
                        @can('viewAny', \App\Models\Course::class)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                            <x-nav-link :href="route('dashboard.courses.index')" :active="request()->routeIs('dashboard.courses.*')">{{ __('Courses') }}</x-nav-link>
                            <x-nav-link :href="route('dashboard.settings.edit')" :active="request()->routeIs('dashboard.settings.*')">{{ __('Settings') }}</x-nav-link>
                            <x-nav-link :href="route('dashboard.appearance.edit')" :active="request()->routeIs('dashboard.appearance.*')">{{ __('Appearance') }}</x-nav-link>
                            <x-nav-link :href="route('dashboard.users.index')" :active="request()->routeIs('dashboard.users.*')">{{ __('Users') }}</x-nav-link>
                            <x-nav-link :href="route('dashboard.finance.index')" :active="request()->routeIs('dashboard.finance.*')">{{ __('Finance') }}</x-nav-link>
                            <x-nav-link :href="url('/')" :active="request()->is('/')">{{ __('View Site') }}</x-nav-link>
                        @else
                            <x-nav-link :href="url('/')" :active="request()->is('/')">{{ __('Home') }}</x-nav-link>
                            <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index') || request()->routeIs('courses.show')">{{ __('Browse Courses') }}</x-nav-link>
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('My Courses') }}</x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <button type="button" data-theme-toggle class="cf-theme-toggle !h-11 !w-11 !justify-center !rounded-2xl !px-0 !py-0" aria-label="{{ __('Toggle theme') }}" title="{{ __('Toggle theme') }}">
                    <span class="cf-theme-icon-shell">
                        <svg data-theme-icon="light" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 3v2.5M12 18.5V21M4.93 4.93l1.77 1.77M17.3 17.3l1.77 1.77M3 12h2.5M18.5 12H21M4.93 19.07l1.77-1.77M17.3 6.7l1.77-1.77M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg data-theme-icon="dark" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M20 15.5A8.5 8.5 0 118.5 4 6.5 6.5 0 0020 15.5z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>

                @auth
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="cf-nav-surface focus:outline-none focus:ring-2 focus:ring-[rgba(193,18,31,0.18)]">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--color-primary)] text-sm font-semibold text-white">
                                    {{ \Illuminate\Support\Str::substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <span class="text-start">
                                    <span class="block text-sm font-semibold text-[var(--color-text-primary)]">{{ Auth::user()->name }}</span>
                                    <span class="block text-xs text-[var(--color-text-muted)]">{{ __('Account') }}</span>
                                </span>
                                <svg class="h-4 w-4 text-[var(--color-text-muted)]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="cf-nav-surface !justify-center">{{ __('Log in') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="cf-button-primary">{{ __('Create account') }}</a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center lg:hidden">
                <button @click="open = ! open" class="cf-button-secondary !rounded-2xl !px-3 !py-2" aria-label="{{ __('Toggle navigation') }}">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition class="cf-mobile-shell">
        <div class="cf-shell py-4">
            <div class="space-y-2">
                @guest
                    <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">{{ __('Home') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index') || request()->routeIs('courses.show')">{{ __('Courses') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('instructor.show')" :active="request()->routeIs('instructor.show')">{{ __('Instructor') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">{{ __('Login') }}</x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">{{ __('Register') }}</x-responsive-nav-link>
                    @endif
                @endguest

                <button type="button" data-theme-toggle class="cf-theme-toggle mt-2 !h-11 !w-11 !justify-center !rounded-2xl !px-0 !py-0 lg:hidden" aria-label="{{ __('Toggle theme') }}" title="{{ __('Toggle theme') }}">
                    <span class="cf-theme-icon-shell">
                        <svg data-theme-icon="light" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M12 3v2.5M12 18.5V21M4.93 4.93l1.77 1.77M17.3 17.3l1.77 1.77M3 12h2.5M18.5 12H21M4.93 19.07l1.77-1.77M17.3 6.7l1.77-1.77M16 12a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg data-theme-icon="dark" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M20 15.5A8.5 8.5 0 118.5 4 6.5 6.5 0 0020 15.5z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </button>

                @auth
                    @can('viewAny', \App\Models\Course::class)
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('dashboard.courses.index')" :active="request()->routeIs('dashboard.courses.*')">{{ __('Courses') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('dashboard.settings.edit')" :active="request()->routeIs('dashboard.settings.*')">{{ __('Settings') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('dashboard.appearance.edit')" :active="request()->routeIs('dashboard.appearance.*')">{{ __('Appearance') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('dashboard.users.index')" :active="request()->routeIs('dashboard.users.*')">{{ __('Users') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('dashboard.finance.index')" :active="request()->routeIs('dashboard.finance.*')">{{ __('Finance') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">{{ __('View Site') }}</x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">{{ __('Home') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index') || request()->routeIs('courses.show')">{{ __('Browse Courses') }}</x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('My Courses') }}</x-responsive-nav-link>
                    @endcan

                    <div class="mt-4 rounded-[24px] border border-[rgba(193,18,31,0.12)] bg-[rgba(193,18,31,0.06)] px-4 py-4 backdrop-blur">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ Auth::user()->name }}</p>
                        <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ Auth::user()->email }}</p>
                        <div class="mt-4 space-y-2">
                            <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
