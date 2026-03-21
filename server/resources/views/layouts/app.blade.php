<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="referrer" content="strict-origin-when-cross-origin">
        <link rel="icon" type="image/png" sizes="any" href="{{ url('/favicon.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/favicon.png') }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <script>
            (function () {
                try {
                    var storedTheme = window.localStorage.getItem('courseflow-theme');
                    var defaultTheme = @json($defaultUiTheme ?? 'system');
                    var theme = storedTheme || (defaultTheme === 'system'
                        ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                        : defaultTheme);
                    document.documentElement.classList.toggle('theme-dark', theme === 'dark');
                    document.documentElement.dataset.theme = theme;
                } catch (error) {
                    document.documentElement.dataset.theme = 'light';
                }
            })();
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --color-primary: {{ $theme['primary'] ?? '#F5B800' }};
                --color-primary-hover: {{ $theme['primary_hover'] ?? '#D8A100' }};
                --color-secondary: {{ $theme['secondary'] ?? '#0B0B0B' }};
                --color-accent: {{ $theme['accent'] ?? '#F7F7F7' }};
                --color-bg: {{ $theme['bg'] ?? '#FFFFFF' }};
                --color-background: {{ $theme['bg'] ?? '#FFFFFF' }};
                --color-text: {{ $theme['text'] ?? '#0B0B0B' }};
                --color-text-primary: {{ $theme['text'] ?? '#0B0B0B' }};
                --color-text-muted: {{ $theme['text_muted'] ?? '#4A4A4A' }};
                --color-error: {{ $theme['error'] ?? '#DC2626' }};
            }
        </style>
    </head>
    <body class="antialiased" @if(isset($rightClickEnabled) && ! $rightClickEnabled) oncontextmenu="return false" @endif data-right-click-enabled="{{ isset($rightClickEnabled) && $rightClickEnabled ? '1' : '0' }}">
        @if (session('status'))
            <div
                x-data="{ visible: true }"
                x-init="setTimeout(() => visible = false, 3600)"
                x-show="visible"
                x-transition.opacity.duration.250ms
                class="fixed right-4 top-4 z-[90] sm:right-6 sm:top-6"
            >
                <div class="cf-admin-toast">
                    <div class="cf-admin-toast-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ __('Saved successfully') }}</p>
                        <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif
        <div class="cf-app-shell">
            @include('layouts.navigation')

            @auth
                @can('viewAny', \App\Models\Course::class)
                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-64 bg-white border-r border-gray-200 min-h-screen">
                            <nav class="mt-8">
                                <div class="px-4">
                                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Admin Panel') }}</h2>
                                    <ul class="space-y-2">
                                        <li>
                                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Dashboard') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.courses.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.courses.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Courses') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.books.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.books.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Books') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.users.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Users') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.finance.index') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.finance.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Finance') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.appearance.edit') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.appearance.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Appearance') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.settings.edit') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.settings.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Settings') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('dashboard.instructor_profile.edit') }}" class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('dashboard.instructor_profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                {{ __('Profile') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </aside>
                        <!-- Main content -->
                        <main class="flex-1 cf-shell py-8 sm:py-10 lg:py-12">
                            @isset($header)
                                <header class="cf-page-header mb-8 text-white">
                                    <div class="relative z-10">
                                        {{ $header }}
                                    </div>
                                </header>
                            @endisset

                            {{ $slot }}
                        </main>
                    </div>
                @else
                    <main class="cf-shell py-8 sm:py-10 lg:py-12">
                        @isset($header)
                            <header class="cf-page-header mb-8 text-white">
                                <div class="relative z-10">
                                    {{ $header }}
                                </div>
                            </header>
                        @endisset

                        {{ $slot }}
                    </main>
                @endcan
            @else
                <main class="cf-shell py-8 sm:py-10 lg:py-12">
                    @isset($header)
                        <header class="cf-page-header mb-8 text-white">
                            <div class="relative z-10">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    {{ $slot }}
                </main>
            @endauth
        </div>
    </body>
</html>
