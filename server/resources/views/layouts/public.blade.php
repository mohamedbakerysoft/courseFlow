<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="referrer" content="strict-origin-when-cross-origin">
        <link rel="icon" type="image/png" sizes="any" href="{{ url('/favicon.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/favicon.png') }}">
        <title>{{ $title ?? ($siteBrandName ?? config('app.name', 'Learnova')) }}</title>
        <meta name="description" content="{{ $metaDescription ?? '' }}">
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
                --color-error: {{ $theme['error'] ?? '#dc2626' }};
                --hero-title-size: {{ $heroTypography['title'] ?? '76px' }};
                --hero-subtitle-size: {{ $heroTypography['subtitle'] ?? '22px' }};
                --hero-description-size: {{ $heroTypography['description'] ?? '18px' }};
            }
        </style>
    </head>
    <body class="bg-[var(--color-background)] text-[var(--color-text-primary)] antialiased" @if(isset($rightClickEnabled) && ! $rightClickEnabled) oncontextmenu="return false" @endif data-right-click-enabled="{{ isset($rightClickEnabled) && $rightClickEnabled ? '1' : '0' }}">
        <div class="relative isolate overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[28rem] bg-[radial-gradient(circle_at_top_left,_rgba(245,184,0,0.18),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(11,11,11,0.06),_transparent_36%)]"></div>
            @include('layouts.navigation')
            <main class="relative">
                {{ $slot }}
            </main>
            <footer class="cf-shell py-14">
                @php
                    $contactEnabled = app(\App\Services\SettingsService::class)->get('landing.show_contact_form', true);
                    $footerContactUrl = $contactEnabled ? url('/#contact') : route('instructor.show');
                    $footerContactLabel = $contactEnabled ? __('Contact') : __('Instructor');
                @endphp
                <div class="cf-panel-dark overflow-hidden px-6 py-8 sm:px-8 lg:px-10">
                    <div class="grid gap-8 lg:grid-cols-[1.4fr,0.8fr] lg:items-end">
                        <div class="space-y-4">
                            <span class="cf-dark-kicker">
                                {{ __('Premium learning storefront') }}
                            </span>
                            <div class="max-w-2xl space-y-3">
                                <h2 class="cf-dark-title text-2xl font-bold tracking-[-0.04em] sm:text-3xl">
                                    Learn, enroll, and reach the first lesson with confidence
                                </h2>
                                <p class="cf-dark-copy max-w-xl text-base leading-8">
                                    A clear catalog, safer checkout flow, and structured lesson journey built for independent instructors.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-3 text-sm">
                                <span class="cf-dark-chip">Secure payments</span>
                                <span class="cf-dark-chip">Instant access</span>
                                <span class="cf-dark-chip">Saved progress</span>
                            </div>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                            <a href="/terms" class="cf-dark-link-card">{{ __('Terms') }}</a>
                            <a href="/privacy" class="cf-dark-link-card">{{ __('Privacy') }}</a>
                        </div>
                    </div>
                    <div class="cf-dark-muted mt-8 border-t border-white/10 pt-6 text-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                            <p>{{ __('A modern storefront built for focused, independent course businesses.') }}</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <x-live-chat-floating
            :enabled="(bool) ($liveChat['enabled'] ?? false)"
            :provider="(string) ($liveChat['provider'] ?? 'none')"
        />
        @if (($liveChat['provider'] ?? 'none') === 'tawk' && ! empty($liveChat['tawk_embed_code']))
            {!! $liveChat['tawk_embed_code'] !!}
        @endif
    </body>
</html>
