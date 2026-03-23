<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                    {{ __('Manage Lessons') }} - {{ $course->title }}
                </h1>
                <p class="text-sm text-[var(--color-text-muted)]">
                    {{ __('Organize modules and lessons from one page. Drag modules to reorder them, and drag lessons between modules when you want to reshape the course flow.') }}
                </p>
            </div>

            <div class="flex flex-row flex-wrap items-center gap-3 lg:flex-nowrap">
                <a href="{{ route('dashboard.courses.edit', $course) }}" class="inline-flex items-center rounded-md border border-[var(--color-secondary)]/30 bg-white px-4 py-2 text-sm font-medium text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/10">
                    {{ __('Back to Course') }}
                </a>
                <a href="{{ route('dashboard.courses.lessons.create', $course) }}" class="inline-flex items-center rounded-md bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:opacity-90">
                    {{ __('Add Lesson') }}
                </a>
            </div>
        </div>

        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Courses'), 'url' => route('dashboard.courses.index')],
            ['label' => $course->title, 'url' => route('dashboard.courses.edit', $course)],
            ['label' => __('Lessons')],
        ]" />

        <section class="mt-6 rounded-[24px] border border-[var(--color-secondary)]/10 bg-white p-6 shadow-sm">
            <div class="max-w-2xl">
                <h2 class="text-lg font-semibold text-[var(--color-text-primary)]">{{ __('Create New Module') }}</h2>
            </div>

            <form method="POST" action="{{ route('dashboard.courses.modules.store', $course) }}" class="mt-5 space-y-4 max-w-2xl">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Module Title') }}</label>
                    <input name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-xl border border-[var(--color-secondary)]/20 bg-[var(--color-surface)] px-4 py-3" required>
                    @error('title')
                        <p class="mt-2 text-sm text-[var(--color-error)]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Module Description') }}</label>
                    <textarea name="description" rows="4" class="mt-2 w-full rounded-xl border border-[var(--color-secondary)]/20 bg-[var(--color-surface)] px-4 py-3">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-[var(--color-error)]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="inline-flex items-center rounded-md bg-[var(--color-primary)] px-4 py-2 text-sm font-semibold text-white">
                        {{ __('Create Module') }}
                    </button>
                </div>
            </form>
        </section>

        <section
            class="mt-8 space-y-4"
            data-course-organizer
            data-module-reorder-url="{{ route('dashboard.courses.modules.reorder', $course) }}"
            data-lesson-reorder-url="{{ route('dashboard.courses.lessons.reorder', $course) }}"
        >
            <div class="rounded-[20px] border border-dashed border-[var(--color-primary)]/30 bg-[var(--color-primary)]/5 px-4 py-3">
                <p class="font-medium text-[var(--color-text-primary)]">{{ __('Drag modules and lessons directly from this layout.') }}</p>
                <p class="mt-1 text-sm text-[var(--color-text-muted)]" data-course-organizer-status>
                    {{ __('The order you save here is the same order learners will see on the course details page and lesson view.') }}
                </p>
            </div>

            <div data-module-sorter-list class="space-y-5">
                @forelse ($modules as $module)
                    <article
                        x-data="{ open: false, editing: false }"
                        class="overflow-hidden rounded-[22px] border border-[var(--color-secondary)]/10 bg-white shadow-sm"
                        data-module-id="{{ $module->id }}"
                    >
                        <div class="border-b border-[var(--color-secondary)]/10 px-5 py-4">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <button type="button" draggable="true" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 text-lg text-[var(--color-text-muted)] cursor-grab active:cursor-grabbing" title="{{ __('Drag module') }}" data-module-drag-handle>
                                            ⋮⋮
                                        </button>
                                        <span class="cf-badge-muted" data-module-order-badge>{{ __('Module :number', ['number' => $module->position]) }}</span>
                                        <h2 class="truncate text-lg font-semibold text-[var(--color-text-primary)]">{{ $module->title }}</h2>
                                        <span class="text-sm text-[var(--color-text-muted)]">{{ __(':count lessons', ['count' => $module->lessons->count()]) }}</span>
                                    </div>

                                    @if ($module->description)
                                        <p class="mt-3 text-sm leading-7 text-[var(--color-text-muted)]">{{ $module->description }}</p>
                                    @endif

                                    <div x-show="!open" class="mt-3 inline-flex items-center rounded-full border border-[var(--color-secondary)]/15 bg-[var(--color-surface)] px-4 py-2 text-xs font-medium text-[var(--color-text-muted)]">
                                        {{ __('Lessons are collapsed in this module.') }}
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="button" @click="open = !open" class="inline-flex items-center rounded-md border border-[var(--color-secondary)]/20 px-4 py-2 text-sm font-medium text-[var(--color-text-muted)] hover:bg-[var(--color-secondary)]/5">
                                        <span x-text="open ? '{{ __('Collapse Lessons') }}' : '{{ __('Expand Lessons') }}'"></span>
                                    </button>
                                    <button type="button" @click="editing = !editing" class="inline-flex items-center rounded-md border border-[var(--color-primary)]/20 px-4 py-2 text-sm font-medium text-[var(--color-primary)] hover:bg-[var(--color-primary)]/5">
                                        <span x-text="editing ? '{{ __('Close Edit') }}' : '{{ __('Edit Module') }}'"></span>
                                    </button>
                                    <a href="{{ route('dashboard.courses.lessons.create', $course) }}?module_id={{ $module->id }}" class="inline-flex items-center rounded-md border border-[var(--color-primary)]/20 px-4 py-2 text-sm font-medium text-[var(--color-primary)]">
                                        {{ __('Add Lesson Here') }}
                                    </a>
                                </div>
                            </div>

                            <div x-show="editing" class="mt-4 rounded-2xl border border-[var(--color-secondary)]/10 bg-[var(--color-surface)] p-4">
                                <div class="grid gap-4 lg:grid-cols-[1fr,auto] lg:items-start">
                                    <form method="POST" action="{{ route('dashboard.modules.update', $module) }}" class="space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Module Title') }}</label>
                                            <input name="title" type="text" value="{{ $module->title }}" class="mt-2 w-full rounded-xl border border-[var(--color-secondary)]/20 bg-white px-4 py-3" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-[var(--color-text-primary)]">{{ __('Module Description') }}</label>
                                            <textarea name="description" rows="3" class="mt-2 w-full rounded-xl border border-[var(--color-secondary)]/20 bg-white px-4 py-3">{{ $module->description }}</textarea>
                                        </div>
                                        <button type="submit" class="rounded-md border border-[var(--color-primary)]/20 px-4 py-2 text-sm font-medium text-[var(--color-primary)]">
                                            {{ __('Save Module') }}
                                        </button>
                                    </form>

                                    <div class="rounded-xl border border-[var(--color-error)]/10 bg-white px-4 py-4">
                                        <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Danger zone') }}</p>
                                        <p class="mt-2 text-xs leading-6 text-[var(--color-text-muted)]">{{ __('Deleting a module will move its lessons to another available module if one exists.') }}</p>
                                        <form action="{{ route('dashboard.modules.destroy', $module) }}" method="POST" onsubmit="return confirm('{{ __('Delete this module?') }}')" class="mt-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-[var(--color-error)] hover:underline">
                                                {{ __('Delete Module') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="open">
                            <div
                                class="space-y-3 px-5 py-4"
                                data-lesson-list
                                data-module-id="{{ $module->id }}"
                            >
                                @forelse ($module->lessons as $lesson)
                                    <div
                                        class="rounded-[18px] border border-[var(--color-secondary)]/10 bg-[var(--color-surface)] px-4 py-4 transition"
                                        data-lesson-id="{{ $lesson->id }}"
                                    >
                                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-3">
                                                    <button type="button" draggable="true" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-[var(--color-secondary)]/20 bg-white text-lg text-[var(--color-text-muted)] cursor-grab active:cursor-grabbing" title="{{ __('Drag lesson') }}" data-lesson-drag-handle>
                                                        ⋮⋮
                                                    </button>
                                                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[var(--color-primary)]/10 text-sm font-semibold text-[var(--color-primary)]" data-lesson-order-badge>
                                                        {{ $lesson->position }}
                                                    </span>
                                                    <div class="min-w-0">
                                                        <p class="truncate font-medium text-[var(--color-text-primary)]">{{ $lesson->title }}</p>
                                                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ $lesson->slug }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-3">
                                                @if ($lesson->status === \App\Models\Lesson::STATUS_DRAFT)
                                                    <span class="cf-badge-muted">{{ __('Draft') }}</span>
                                                    <form action="{{ route('dashboard.lessons.publish', $lesson) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-sm font-medium text-emerald-600 hover:underline">
                                                            {{ __('Publish') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="cf-badge">{{ __('Published') }}</span>
                                                    <form action="{{ route('dashboard.lessons.unpublish', $lesson) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-sm font-medium text-amber-600 hover:underline">
                                                            {{ __('Unpublish') }}
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('dashboard.lessons.edit', $lesson) }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">
                                                    {{ __('Edit') }}
                                                </a>

                                                <form action="{{ route('dashboard.lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('{{ __('Delete lesson?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-[var(--color-error)] hover:underline">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-[22px] border border-dashed border-[var(--color-secondary)]/20 px-5 py-8 text-center text-sm text-[var(--color-text-muted)]">
                                        {{ __('No lessons inside this module yet. Drag a lesson here or create a new one for this module.') }}
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[24px] border border-[var(--color-secondary)]/10 bg-white px-5 py-10 text-center shadow-sm">
                        <p class="font-medium text-[var(--color-text-primary)]">{{ __('No modules yet.') }}</p>
                        <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ __('Create the first module above, then start adding and arranging lessons.') }}</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
