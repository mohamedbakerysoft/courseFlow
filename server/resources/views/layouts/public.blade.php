<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'CourseFlow') }}</title>
        <meta name="description" content="{{ $metaDescription ?? '' }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <header class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="/" class="font-semibold">CourseFlow</a>
                <nav class="space-x-4">
                    <a href="{{ route('courses.index') }}" class="text-sm text-gray-700 hover:underline">Courses</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 hover:underline">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:underline">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ms-4 text-sm text-gray-700 hover:underline">Register</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>
        <main class="max-w-7xl mx-auto px-4 py-8">
            {{ $slot }}
        </main>
    </body>
</html>
