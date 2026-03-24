<x-public-layout :title="$heading" :metaDescription="$subheading">
    <section class="cf-shell cf-section pt-10 sm:pt-14">
        <x-breadcrumbs :items="[
            ['label' => __('Home'), 'url' => url('/')],
            ['label' => __('FAQ')],
        ]" />

        <div class="mt-6 max-w-3xl space-y-4">
            <span class="cf-kicker">{{ __('FAQ') }}</span>
            <h1 class="cf-display text-4xl sm:text-5xl">{{ $heading }}</h1>
            @if ($subheading !== '')
                <p class="cf-subheading max-w-3xl">{{ $subheading }}</p>
            @endif
        </div>
    </section>

    <section class="cf-shell pb-14 sm:pb-16 lg:pb-20">
        <div class="grid gap-4">
            @forelse ($faqs as $faq)
                <article class="cf-panel px-6 py-6 sm:px-8">
                    <h2 class="text-xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">{{ $faq->question }}</h2>
                    <p class="mt-3 text-base leading-8 text-[var(--color-text-muted)]">{{ $faq->answer }}</p>
                </article>
            @empty
                <div class="cf-panel px-8 py-10 text-center text-[var(--color-text-muted)]">
                    {{ __('No FAQ items are published yet.') }}
                </div>
            @endforelse
        </div>
    </section>
</x-public-layout>
