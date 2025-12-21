<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(isset($isRtl) && $isRtl) dir="rtl" class="rtl" @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --font-arabic: {{ $typographyCss['arabic_stack'] ?? "'Cairo', sans-serif" }};
                --font-english: {{ $typographyCss['english_stack'] ?? "'Inter', system-ui, sans-serif" }};
            }
            html { font-family: var(--font-english); }
            html[lang="ar"], .rtl { font-family: var(--font-arabic); }
        </style>
    </head>
    <body class="text-gray-900 antialiased">
        @include('layouts.navigation')
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    @if (!empty($siteLogoUrl))
                        <img src="{{ $siteLogoUrl }}" alt="{{ config('app.name') }}" class="w-20 h-20">
                    @else
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
