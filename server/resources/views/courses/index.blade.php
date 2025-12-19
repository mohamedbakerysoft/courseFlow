<x-public-layout :title="'Browse Courses'" :metaDescription="'Browse published courses'">
    <section class="space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Browse Courses</h1>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($courses as $course)
                <x-course.card :course="$course" />
            @empty
                <div class="col-span-full bg-white rounded-xl shadow-lg ring-1 ring-gray-100 p-10 text-center">
                    <div class="text-5xl mb-4">🎓</div>
                    <p class="text-gray-800 font-semibold text-lg">No courses yet</p>
                    <p class="text-gray-500 text-sm mt-1">Please check back soon.</p>
                    <div class="mt-6">
                        <a href="{{ url('/') }}" class="inline-flex items-center px-5 py-2.5 rounded bg-[var(--color-primary)] text-white">Go to homepage</a>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    </section>
</x-public-layout>
