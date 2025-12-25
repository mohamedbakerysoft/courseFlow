<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(isset($isRtl) && $isRtl) dir="rtl" class="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                --hero-title-size: {{ $heroTypography['title'] ?? '56px' }};
                --hero-subtitle-size: {{ $heroTypography['subtitle'] ?? '24px' }};
                --hero-description-size: {{ $heroTypography['description'] ?? '18px' }};
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
                            <span>{{ $locale === 'ar' ? 'الدفع عبر سترايب وباي بال' : 'Payments handled via Stripe & PayPal' }}</span>
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
            $enabled = false;
            $phone = '';
            $message = '';
            if (!empty($whatsappCta) && ($whatsappCta['enabled'] ?? false)) {
                $enabled = true;
                $phone = (string) ($whatsappCta['phone'] ?? '');
                $message = (string) ($whatsappCta['message'] ?? '');
            } else {
                $enabled = (bool) (\App\Models\Setting::query()->where('key', 'contact.whatsapp.enabled')->value('value') ?? false);
                $phone = (string) (\App\Models\Setting::query()->where('key', 'contact.whatsapp.phone')->value('value') ?? '');
                $message = (string) (\App\Models\Setting::query()->where('key', 'contact.whatsapp.message')->value('value') ?? '');
            }
        @endphp
        <x-whatsapp-floating :enabled="$enabled" :phone="$phone" :message="$message" />
    </body>
</html>
