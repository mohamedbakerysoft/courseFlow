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
            @php $locale = app()->getLocale(); @endphp
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="text-center sm:text-start">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                    <div class="flex items-center justify-center sm:justify-start gap-3 mt-2 text-xs">
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-4 w-4 text-[var(--color-accent)]" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>{{ $locale === 'ar' ? 'دفع آمن' : 'Secure payments' }}</span>
                        </span>
                        <span>·</span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-4 w-4 text-[var(--color-primary)]" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M13 3L4 14h7l-1 7 9-11h-7l1-7z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>{{ $locale === 'ar' ? 'وصول فوري' : 'Instant access' }}</span>
                        </span>
                        <span>·</span>
                        <span class="inline-flex items-center gap-1">
                            <svg class="h-4 w-4 text-[var(--color-secondary)]" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l7 4v5c0 5-4 8-7 9-3-1-7-4-7-9V7l7-4z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>{{ $locale === 'ar' ? 'خصوصية أولاً' : 'Privacy‑first' }}</span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/terms" class="hover:text-[var(--color-text-primary)]">{{ __('Terms') }}</a>
                    <a href="/privacy" class="hover:text-[var(--color-text-primary)]">{{ __('Privacy') }}</a>
                    <a href="/#contact" class="hover:text-[var(--color-text-primary)]">{{ __('Contact') }}</a>
                </div>
            </div>
        </footer>
        @php
            $waEnabled = (bool) (\App\Models\Setting::query()->where('key', 'contact.whatsapp.enabled')->value('value') ?? false);
            $waPhoneRaw = (string) (\App\Models\Setting::query()->where('key', 'contact.whatsapp.phone')->value('value') ?? '');
            $waMessageRaw = (string) (\App\Models\Setting::query()->where('key', 'contact.whatsapp.message')->value('value') ?? 'Hello! I have a question about your courses.');
            $waPhone = preg_replace('/[^0-9]/', '', $waPhoneRaw);
            $waMessage = trim($waMessageRaw);
            $waLink = 'https://wa.me/'.$waPhone.'?text='.urlencode($waMessage);
        @endphp
        @if ($waEnabled && $waPhone !== '')
            <a href="{{ $waLink }}"
               class="fixed bottom-6 right-6 inline-flex items-center gap-2 px-4 py-3 rounded-full shadow-lg bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]"
               aria-label="{{ __('Chat on WhatsApp') }}"
               target="_blank"
               rel="noopener">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.94 14.5L2 22l5.62-1.48A10 10 0 1 0 12 2zm5.2 14.24c-.22.62-1.28 1.18-1.78 1.23-.46.05-1.05.08-2.58-.54-2.16-.89-3.55-3.08-3.66-3.22-.1-.15-.87-1.15-.87-2.2 0-1.05.55-1.56.75-1.78.2-.22.48-.28.64-.28h.46c.15 0 .36-.06 .55 .41 .22 .54 .74 1.86 .81 1.99 .07 .13 .12 .3 .02 .48 -.09 .17 -.15 .27 -.29 .42 -.15 .16 -.31 .35 -.45 .47 -.15 .12 -.3 .26 -.13 .52 .17 .27 .76 1.23 1.65 1.99 1.14 .94 2.1 1.23 2.39 1.37 .29 .14 .46 .12 .63 -.07 .17 -.2 .72 -.84 .91 -1.13 .19 -.29 .39 -.24 .63 -.14 .24 .1 1.51 .71 1.77 .84 .26 .13 .43 .19 .5 .3 .06 .11 .06 .64 -.17 1.26 z"/></svg>
                <span class="hidden sm:inline">{{ __('WhatsApp') }}</span>
            </a>
        @endif
        @if (!empty($whatsappCta) && ($whatsappCta['enabled'] ?? false))
            @php
                $waPhone = preg_replace('/[^0-9]/', '', $whatsappCta['phone'] ?? '');
                $waMessage = trim((string) ($whatsappCta['message'] ?? 'Hello! I have a question about your courses.'));
                $waLink = 'https://wa.me/'.$waPhone.'?text='.urlencode($waMessage);
            @endphp
            <a href="{{ $waLink }}"
               class="fixed bottom-6 right-6 inline-flex items-center gap-2 px-4 py-3 rounded-full shadow-lg bg-[var(--color-primary)] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]"
               aria-label="{{ __('Chat on WhatsApp') }}"
               target="_blank"
               rel="noopener">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.94 14.5L2 22l5.62-1.48A10 10 0 1 0 12 2zm5.2 14.24c-.22.62-1.28 1.18-1.78 1.23-.46.05-1.05.08-2.58-.54-2.16-.89-3.55-3.08-3.66-3.22-.1-.15-.87-1.15-.87-2.2 0-1.05.55-1.56.75-1.78.2-.22.48-.28.64-.28h.46c.15 0 .36-.06.55.41.22.54.74 1.86.81 1.99.07.13.12.3.02.48-.09.17-.15.27-.29.42-.15.16-.31.35-.45.47-.15.12-.3.26-.13.52.17.27.76 1.23 1.65 1.99 1.14.94 2.1 1.23 2.39 1.37.29.14.46.12.63-.07.17-.2.72-.84.91-1.13.19-.29.39-.24.63-.14.24.1 1.51.71 1.77.84.26.13.43.19.5.3.06.11.06.64-.17 1.26z"/></svg>
                <span class="hidden sm:inline">{{ __('WhatsApp') }}</span>
            </a>
        @endif
    </body>
</html>
