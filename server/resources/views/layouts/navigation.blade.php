<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        @if (!empty($siteLogoUrl))
                            <img src="{{ $siteLogoUrl }}" alt="{{ config('app.name') }}" class="block h-9 w-auto">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-[var(--color-text-primary)]" />
                        @endif
                    </a>
                </div>
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    @guest
                        <x-nav-link :href="url('/')" :active="request()->is('/')">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">
                            {{ __('Courses') }}
                        </x-nav-link>
                        <x-nav-link :href="route('instructor.show')" :active="request()->routeIs('instructor.show')">
                            {{ __('Instructor') }}
                        </x-nav-link>
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Login') }}
                        </x-nav-link>
                        @if (Route::has('register'))
                            <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                                {{ __('Register') }}
                            </x-nav-link>
                        @endif
                    @endguest
                    @auth
                        @can('viewAny', \App\Models\Course::class)
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dashboard.courses.index')" :active="request()->routeIs('dashboard.courses.*')">
                                {{ __('Courses') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dashboard.settings.edit')" :active="request()->routeIs('dashboard.settings.*')">
                                {{ __('Settings') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dashboard.appearance.edit')" :active="request()->routeIs('dashboard.appearance.*')">
                                {{ __('Appearance') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dashboard.instructor_profile.edit')" :active="request()->routeIs('dashboard.instructor_profile.*')">
                                {{ __('Instructor Profile') }}
                            </x-nav-link>
                            <x-nav-link :href="url('/')" :active="request()->is('/')">
                                {{ __('View Site') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="url('/')" :active="request()->is('/')">
                                {{ __('Home') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('My Courses') }}
                            </x-nav-link>
                            <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">
                                {{ __('Browse Courses') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border text-sm leading-4 font-medium rounded-md text-[var(--color-text-primary)] bg-white border-[var(--color-secondary)]/30 hover:bg-[var(--color-secondary)]/10 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center px-3 py-2 rounded bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-hover)]">{{ __('Log in') }}</a>
                @endauth
            </div>
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @guest
                <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">
                    {{ __('Courses') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('instructor.show')" :active="request()->routeIs('instructor.show')">
                    {{ __('Instructor') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Login') }}
                </x-responsive-nav-link>
                @if (Route::has('register'))
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            @endguest
                @auth
                @can('viewAny', \App\Models\Course::class)
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dashboard.courses.index')" :active="request()->routeIs('dashboard.courses.*')">
                        {{ __('Courses') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dashboard.settings.edit')" :active="request()->routeIs('dashboard.settings.*')">
                        {{ __('Settings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dashboard.appearance.edit')" :active="request()->routeIs('dashboard.appearance.*')">
                        {{ __('Appearance') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dashboard.instructor_profile.edit')" :active="request()->routeIs('dashboard.instructor_profile.*')">
                        {{ __('Instructor Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                        {{ __('View Site') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                        {{ __('Home') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('My Courses') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.index')">
                        {{ __('Browse Courses') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                @endcan
            @auth
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            @endauth
            @endauth
        </div>
    </div>
</nav>
