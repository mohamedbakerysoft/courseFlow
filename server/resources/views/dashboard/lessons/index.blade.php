<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
            <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                {{ __('Lessons for') }} {{ $course->title }}
            </h1>
            <p class="text-sm text-[var(--color-text-muted)]">{{ __('Organize lessons to structure your course content.') }}</p>
            </div>
            @isset($course)
                <a href="{{ route('dashboard.courses.lessons.create', $course) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('Add Lesson') }}
                </a>
            @endisset
        </div>
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Courses'), 'url' => route('dashboard.courses.index')],
            ['label' => __('Lessons')]
        ]" />
        <div class="mt-4 flex items-center justify-end">
            <a href="{{ route('dashboard.courses.edit', $course) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-[var(--color-secondary)]/30 bg-white text-sm font-medium text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                {{ __('Back to Course') }}
            </a>
        </div>
        <div class="mt-4 bg-white rounded-xl shadow-sm border border-[var(--color-secondary)]/10 overflow-hidden">
            <table class="min-w-full divide-y divide-[var(--color-secondary)]/20 text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Slug</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Position</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-secondary)]/10">
                @forelse($lessons as $lesson)
                    <tr>
                        <td class="px-4 py-3 text-[var(--color-text-primary)]">{{ $lesson->title }}</td>
                        <td class="px-4 py-3 text-[var(--color-text-muted)]">{{ $lesson->slug }}</td>
                        <td class="px-4 py-3 text-[var(--color-text-muted)]">{{ $lesson->position }}</td>
                        <td class="px-4 py-3">
                            @if($lesson->status === \App\Models\Lesson::STATUS_DRAFT)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-[var(--color-secondary)]/10 text-[var(--color-secondary)] text-xs">Draft</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-xs">Published</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-x-3 whitespace-nowrap text-sm">
                            <a href="{{ route('dashboard.lessons.edit', $lesson) }}" class="text-[var(--color-secondary)] hover:underline">Edit</a>
                            <form action="{{ route('dashboard.lessons.destroy', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('Delete lesson?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-[var(--color-accent)] hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-[var(--color-text-primary)]">
                            <div class="text-3xl mb-2">🗒️</div>
                            <p class="mb-1">{{ __('No lessons added yet.') }}</p>
                            <p class="mb-4 text-sm text-[var(--color-text-muted)]">{{ __('Create lessons to structure your course content.') }}</p>
                            @isset($course)
                                <a href="{{ route('dashboard.courses.lessons.create', $course) }}" class="inline-flex items-center px-5 py-2.5 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                    {{ __('Add Lesson') }}
                                </a>
                            @endisset
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
