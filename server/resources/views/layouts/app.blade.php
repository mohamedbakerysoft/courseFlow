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
        @php
            $googleFonts = [
                'Poppins' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
            ];
            $englishFontUrl = $googleFonts['Poppins'];
        @endphp
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ $englishFontUrl }}" rel="stylesheet" />
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
                --font-english: {{ $typographyCss['english_stack'] ?? "'Poppins', system-ui, sans-serif" }};
            }
            html { font-family: var(--font-english); }
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
        </div>
    </body>
</html>
