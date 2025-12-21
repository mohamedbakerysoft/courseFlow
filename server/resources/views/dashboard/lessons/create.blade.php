<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Add Lesson') }} — {{ $course->title }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Dashboard</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <a href="{{ route('dashboard.courses.index') }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Courses</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Lessons</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <span class="text-[var(--color-text-muted)]">Add</span>
        </nav>
        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" method="POST" action="{{ route('dashboard.courses.lessons.store', $course) }}" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" required>
                @error('title')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" required>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">Only letters, numbers, and dashes.</p>
                @error('slug')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Video URL</label>
                <input name="video_url" type="url" class="mt-1 w-full border rounded p-2" required>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">Use an embeddable URL (e.g., YouTube embed link).</p>
                @error('video_url')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Position</label>
                <input name="position" type="number" class="mt-1 w-full border rounded p-2" value="1" required>
                @error('position')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <button type="submit" :disabled="isSubmitting" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
