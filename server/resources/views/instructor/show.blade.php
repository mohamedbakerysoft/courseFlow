<x-public-layout :title="__('Platform Owner')" :metaDescription="$instructor->bio ?? ''">
    <section class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1 space-y-4">
            @php($avatar = $instructor->profile_image_path
                ? (\Illuminate\Support\Str::startsWith($instructor->profile_image_path, ['http://', 'https://'])
                    ? $instructor->profile_image_path
                    : asset($instructor->profile_image_path))
                : null)
            @if ($avatar)
                <img src="{{ $avatar }}" alt="{{ $instructor->name }}" class="w-48 h-48 rounded-full object-cover">
            @endif
            <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">{{ $instructor->name }}</h1>
            @if ($instructor->bio)
                <p class="text-[var(--color-text-primary)]">{{ $instructor->bio }}</p>
            @endif
            @if (!empty($links))
                <div class="flex gap-3">
                    @foreach ($links as $label => $url)
                        <a href="{{ $url }}" class="text-[var(--color-primary)] hover:underline" rel="noopener" target="_blank">{{ ucfirst($label) }}</a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="md:col-span-2">
            <h2 class="text-xl font-semibold text-[var(--color-text-primary)] mb-4">{{ __('Published Courses') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($courses as $course)
                    <x-course.card :course="$course" />
                @empty
                    <p class="text-[var(--color-text-muted)]">No published courses yet.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-public-layout>
