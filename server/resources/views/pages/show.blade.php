<x-public-layout :title="$page->title" :metaDescription="str($page->content)->limit(160)">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-[var(--color-secondary)]/10 p-6 sm:p-8">
            <h1 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)] mb-4">
                {{ $page->title }}
            </h1>
            <div class="prose prose-sm sm:prose max-w-none text-[var(--color-text-muted)]">
                {!! nl2br(e($page->content)) !!}
            </div>
        </div>
    </div>
</x-public-layout>
