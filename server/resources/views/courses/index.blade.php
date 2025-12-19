<x-public-layout :title="'Courses'" :metaDescription="'Browse published courses'">
    <section>
        <h1 class="text-3xl font-semibold mb-6">Courses</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($courses as $course)
                <x-course.card :course="$course" />
            @empty
                <div class="col-span-full bg-white rounded shadow p-8 text-center">
                    <div class="text-4xl mb-3">🎓</div>
                    <p class="text-gray-700 font-medium">No courses yet</p>
                    <p class="text-gray-500 text-sm mt-1">Please check back soon.</p>
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 rounded bg-[var(--color-primary)] text-white">Go to homepage</a>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    </section>
</x-public-layout>
