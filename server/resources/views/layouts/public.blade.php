<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(isset($isRtl) && $isRtl) dir="rtl" class="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'CourseFlow') }}</title>
        <meta name="description" content="{{ $metaDescription ?? '' }}">
        @php
            $googleFonts = [
                'Cairo' => 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap',
                'Tajawal' => 'https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap',
                'IBM Plex Arabic' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Arabic:wght@400;600;700&display=swap',
                'Inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
                'Poppins' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap',
                'Roboto' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
            ];
            $arabicFont = $typography['arabic_font'] ?? 'Cairo';
            $englishFont = $typography['english_font'] ?? 'Inter';
            $arabicFontUrl = $googleFonts[$arabicFont] ?? $googleFonts['Cairo'];
            $englishFontUrl = $googleFonts[$englishFont] ?? $googleFonts['Inter'];
        @endphp
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="{{ $englishFontUrl }}" rel="stylesheet" />
        <link href="{{ $arabicFontUrl }}" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --color-primary: {{ $theme['primary'] ?? '#4F46E5' }};
                --color-primary-hover: {{ $theme['primary_hover'] ?? '#4338CA' }};
                --color-secondary: {{ $theme['secondary'] ?? '#334155' }};
                --color-accent: {{ $theme['accent'] ?? '#10B981' }};
                --color-bg: {{ $theme['bg'] ?? '#F8FAFC' }};
                --color-background: {{ $theme['bg'] ?? '#F8FAFC' }};
                --color-text: {{ $theme['text'] ?? '#0F172A' }};
                --color-text-primary: {{ $theme['text'] ?? '#0F172A' }};
                --color-text-muted: {{ $theme['text_muted'] ?? '#64748B' }};
                --color-error: {{ $theme['error'] ?? '#EF4444' }};
                --font-arabic: {{ $typographyCss['arabic_stack'] ?? "'Cairo', sans-serif" }};
                --font-english: {{ $typographyCss['english_stack'] ?? "'Inter', system-ui, sans-serif" }};
            }
            html { font-family: var(--font-english); }
            html[lang="ar"], .rtl { font-family: var(--font-arabic); }
        </style>
    </head>
    <body class="antialiased bg-[var(--color-background)] text-[var(--color-text-primary)]">
        @include('layouts.navigation')
        <main class="max-w-7xl mx-auto px-4 py-8">
            {{ $slot }}
        </main>
        <footer class="max-w-7xl mx-auto px-4 py-8 border-t border-[var(--color-secondary)]/10 text-sm text-[var(--color-text-muted)]">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('pages.terms') }}" class="hover:text-[var(--color-text-primary)]">{{ __('Terms') }}</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-[var(--color-text-primary)]">{{ __('Privacy') }}</a>
                </div>
            </div>
        </footer>
    </body>
</html>
