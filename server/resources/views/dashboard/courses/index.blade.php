<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                {{ __('My Courses') }}
            </h1>
            <a href="{{ route('dashboard.courses.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                {{ __('Add Course') }}
            </a>
        </div>
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Courses')]
        ]" />
        <div class="mt-4 bg-white rounded-xl shadow-sm border border-[var(--color-secondary)]/10 overflow-hidden">
            <table class="min-w-full divide-y divide-[var(--color-secondary)]/20 text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Slug</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-[var(--color-text-muted)] uppercase tracking-wider text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-secondary)]/10">
                @forelse($courses as $course)
                    <tr>
                        <td class="px-4 py-3 text-[var(--color-text-primary)]">{{ $course->title }}</td>
                        <td class="px-4 py-3 text-[var(--color-text-muted)]">{{ $course->slug }}</td>
                        <td class="px-4 py-3">
                            @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-[var(--color-secondary)]/10 text-[var(--color-secondary)] text-xs">Draft</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-[var(--color-accent)]/10 text-[var(--color-accent)] text-xs">Published</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 space-x-3 whitespace-nowrap text-sm">
                            <a href="{{ route('dashboard.courses.edit', $course) }}" class="text-[var(--color-secondary)] hover:underline">Edit</a>
                            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="text-[var(--color-secondary)] hover:underline">Lessons</a>
                            @if($course->status === \App\Models\Course::STATUS_DRAFT)
                                <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.publish', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button :disabled="isSubmitting" class="text-[var(--color-accent)] hover:underline">Publish</button>
                                </form>
                            @else
                                <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button :disabled="isSubmitting" class="text-[var(--color-text-muted)] hover:underline">Unpublish</button>
                                </form>
                            @endif
                            <form x-data="{isSubmitting:false}" x-on:submit="isSubmitting=true" action="{{ route('dashboard.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Delete course?')">
                                @csrf
                                @method('DELETE')
                                <button :disabled="isSubmitting" class="text-[var(--color-accent)] hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-[var(--color-text-primary)]">
                            <div class="text-3xl mb-3">📚</div>
                            <p class="mb-1">{{ __('You have not created any courses yet.') }}</p>
                            <p class="mb-4 text-sm text-[var(--color-text-muted)]">{{ __('Create your first course to start selling.') }}</p>
                            <a href="{{ route('dashboard.courses.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-md bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                {{ __('Add Course') }}
                            </a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
