<x-public-layout :title="$page->title" :metaDescription="str($page->content)->limit(160)">
    <article class="prose max-w-none">
        <h1 class="text-3xl font-semibold mb-4">{{ $page->title }}</h1>
        {!! nl2br(e($page->content)) !!}
    </article>
</x-public-layout>
