<x-public-layout :title="'Courses'" :metaDescription="'Browse published courses'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('Courses')],
        ]" />

        <section class="space-y-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-[var(--color-text-primary)]">
                    {{ __('Courses') }}
                </h1>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">
                    {{ __('Browse published courses and find the right one for you.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <x-course.card :course="$course" />
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-10 text-center">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)]">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 7l8-4 8 4-8 4-8-4z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                                <path d="M6 10v7a2 2 0 002 2h8a2 2 0 002-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                        <p class="text-[var(--color-text-primary)] font-semibold text-lg">
                            {{ __('No courses available yet') }}
                        </p>
                        <p class="text-[var(--color-text-muted)] text-sm mt-1">
                            {{ __('Once courses are published, they will appear here.') }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-sm font-semibold text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                                {{ __('Back to Home') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </section>
    </div>
</x-public-layout>
