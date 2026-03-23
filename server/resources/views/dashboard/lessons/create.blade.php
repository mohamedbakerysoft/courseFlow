<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Add Lesson') }} — {{ $course->title }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        @if ($modules->isEmpty())
            <div class="mb-5 rounded-xl border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 px-4 py-3 text-sm text-[var(--color-text-primary)]">
                {{ __('You need to create at least one module before adding lessons to this course.') }}
                <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="ml-2 font-semibold text-[var(--color-primary)] hover:underline">{{ __('Manage modules') }}</a>
            </div>
        @endif
        <nav class="mb-4 text-sm">
            <a href="{{ route('dashboard') }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Dashboard</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <a href="{{ route('dashboard.courses.index') }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Courses</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="underline text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">Lessons</a>
            <span class="text-[var(--color-text-muted)]">/</span>
            <span class="text-[var(--color-text-muted)]">Add</span>
        </nav>
        <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" method="POST" action="{{ route('dashboard.courses.lessons.store', $course) }}" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium">Module</label>
                <select name="module_id" class="mt-1 w-full border rounded p-2" required @disabled($modules->isEmpty())>
                    <option value="">{{ __('Select module') }}</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}" @selected(old('module_id', $selectedModuleId ?? null) == $module->id)>{{ $module->position }}. {{ $module->title }}</option>
                    @endforeach
                </select>
                @error('module_id')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
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
                <input name="video_url" type="url" class="mt-1 w-full border rounded p-2" value="{{ old('video_url') }}">
                <p class="text-xs text-[var(--color-text-muted)] mt-1">Add a YouTube/video URL, or leave this empty and upload an MP4 below.</p>
                @error('video_url')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">MP4 Video Upload</label>
                <input name="video_file" type="file" accept="video/mp4" class="mt-1 w-full border rounded p-2">
                <p class="text-xs text-[var(--color-text-muted)] mt-1">Upload your own MP4 file instead of using a YouTube link.</p>
                @error('video_file')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <button type="submit" :disabled="isSubmitting || {{ $modules->isEmpty() ? 'true' : 'false' }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] disabled:cursor-not-allowed disabled:opacity-60">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
