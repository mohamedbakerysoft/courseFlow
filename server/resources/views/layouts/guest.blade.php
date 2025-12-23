<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(isset($isRtl) && $isRtl) dir="rtl" class="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="referrer" content="strict-origin-when-cross-origin">
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
                --color-error: {{ $theme['error'] ?? '#dc2626' }};
                --font-arabic: {{ $typographyCss['arabic_stack'] ?? "'Alexandria', sans-serif" }};
                --font-english: {{ $typographyCss['english_stack'] ?? "'Poppins', system-ui, sans-serif" }};
            }
            html { font-family: var(--font-english); }
            html[lang="ar"], .rtl { font-family: var(--font-arabic); }
        </style>
    </head>
    <body class="bg-[var(--color-background)] text-[var(--color-text-primary)] antialiased">
        @include('layouts.navigation')
        <div class="cf-shell min-h-[calc(100vh-4rem)] py-12 sm:py-16">
            <div class="grid gap-10 lg:grid-cols-[1fr,0.9fr] lg:items-center">
                <div class="space-y-6">
                    <span class="cf-kicker">
                        {{ app()->getLocale() === 'ar' ? 'بوابة الدخول' : 'Member access' }}
                    </span>
                    <div class="max-w-xl space-y-4">
                        <h1 class="cf-display text-4xl sm:text-5xl lg:text-[3.6rem]">
                            {{ app()->getLocale() === 'ar' ? 'واجهة دخول أنيقة لطلابك ومدرّسيك' : 'A refined sign-in experience for students and instructors' }}
                        </h1>
                        <p class="cf-subheading">
                            {{ app()->getLocale() === 'ar' ? 'تجربة تسجيل واضحة ومنظمة تمتد من الاكتشاف وحتى الوصول إلى لوحة التحكم.' : 'A clear, organized account flow that carries from discovery into login, enrollment, and dashboard access.' }}
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="cf-panel-soft px-5 py-5">
                            <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ app()->getLocale() === 'ar' ? 'تسجيل سريع' : 'Fast sign-in' }}</p>
                            <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ app()->getLocale() === 'ar' ? 'خطوات أقل ووصول أوضح.' : 'Fewer steps and clearer actions.' }}</p>
                        </div>
                        <div class="cf-panel-soft px-5 py-5">
                                <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ app()->getLocale() === 'ar' ? 'ثقة أعلى' : 'Higher trust' }}</p>
                            <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ app()->getLocale() === 'ar' ? 'تجربة متناسقة مع مسار الشراء.' : 'Aligned with the course sales journey.' }}</p>
                        </div>
                        <div class="cf-panel-soft px-5 py-5">
                            <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ app()->getLocale() === 'ar' ? 'جاهزة للعربية' : 'RTL ready' }}</p>
                            <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ app()->getLocale() === 'ar' ? 'تعمل بسلاسة بالعربية والإنجليزية.' : 'Works elegantly in Arabic and English.' }}</p>
                        </div>
                    </div>
                </div>

                <div class="cf-auth-shell">
                    <div class="pointer-events-none absolute -top-14 end-0 h-36 w-36 rounded-full bg-[var(--color-primary)]/12 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-10 start-0 h-28 w-28 rounded-full bg-[var(--color-accent)]/12 blur-3xl"></div>
                    <div class="relative">
                        <div class="mb-8 flex items-center gap-4">
                            <a href="/">
                                @if (!empty($siteLogoUrl))
                                    <img src="{{ $siteLogoUrl }}" alt="{{ config('app.name') }}" class="h-14 w-auto rounded-2xl">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--color-secondary)] text-white shadow-lg">
                                        <x-branding-logo class="h-8 w-8 fill-current text-white" />
                                    </div>
                                @endif
                            </a>
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[var(--color-text-muted)]">{{ config('app.name') }}</p>
                                <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ app()->getLocale() === 'ar' ? 'تجربة دخول واضحة وسريعة.' : 'Clear access for instructors and students.' }}</p>
                            </div>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
