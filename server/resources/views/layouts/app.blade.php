<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(isset($isRtl) && $isRtl) dir="rtl" class="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                'Cairo' => 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap',
                'Tajawal' => 'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap',
                'IBM Plex Arabic' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Arabic:wght@400;500;600;700&display=swap',
                'Alexandria' => 'https://fonts.googleapis.com/css2?family=Alexandria:wght@400;500;600;700;800&display=swap',
                'Noto Sans Arabic' => 'https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap',
                'Inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
                'Poppins' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
                'Roboto' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
                'Plus Jakarta Sans' => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
                'Manrope' => 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap',
                'Instrument Sans' => 'https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap',
            ];
            $arabicFont = $typography['arabic_font'] ?? 'Alexandria';
            $englishFont = $typography['english_font'] ?? 'Poppins';
            $arabicFontUrl = $googleFonts[$arabicFont] ?? $googleFonts['Alexandria'];
            $englishFontUrl = $googleFonts[$englishFont] ?? $googleFonts['Poppins'];
        @endphp
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ $englishFontUrl }}" rel="stylesheet" />
        <link href="{{ $arabicFontUrl }}" rel="stylesheet" />
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
                --font-arabic: {{ $typographyCss['arabic_stack'] ?? "'Alexandria', sans-serif" }};
                --font-english: {{ $typographyCss['english_stack'] ?? "'Poppins', system-ui, sans-serif" }};
            }
            html { font-family: var(--font-english); }
            html[lang="ar"], .rtl { font-family: var(--font-arabic); }
        </style>
    </head>
    <body class="antialiased" @if(isset($rightClickEnabled) && ! $rightClickEnabled) oncontextmenu="return false" @endif data-right-click-enabled="{{ isset($rightClickEnabled) && $rightClickEnabled ? '1' : '0' }}">
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
