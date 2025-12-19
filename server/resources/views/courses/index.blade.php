<x-public-layout :title="'Courses'" :metaDescription="'Browse published courses'">
    <section>
        <h1 class="text-3xl font-semibold mb-6">Courses</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($courses as $course)
                <x-course.card :course="$course" />
            @empty
                <p class="text-gray-600">No courses available.</p>
            @endforelse
        </div>
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
    </section>
</x-public-layout>

