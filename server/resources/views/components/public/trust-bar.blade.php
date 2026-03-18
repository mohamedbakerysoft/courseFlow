@props(['copy' => [], 'courses' => collect()])

@php
    $courseCards = collect($courses)->take(4)->values();
@endphp

<section aria-label="{{ __('Platform proof') }}">
    <div class="cf-section-shell cf-trust-shell !px-5 !py-5 sm:!px-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="cf-trust-card">
                <div class="cf-trust-card-media">
                    <img src="{{ $courseCards->get(0)?->thumbnail_url ?? \App\Support\MediaAsset::courseFallback('trust-1') }}" alt="{{ $copy['trust_1_title'] ?? __('Fast, familiar checkout') }}" loading="lazy">
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_1_title'] ?? __('Fast, familiar checkout') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_1_body'] ?? __('Card, PayPal, and manual payment options are presented in one clean flow.') }}</p>
                </div>
            </div>
            <div class="cf-trust-card">
                <div class="cf-trust-card-media">
                    <img src="{{ $courseCards->get(1)?->thumbnail_url ?? \App\Support\MediaAsset::courseFallback('trust-2') }}" alt="{{ $copy['trust_2_title'] ?? __('Instant enrollment access') }}" loading="lazy">
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_2_title'] ?? __('Instant enrollment access') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_2_body'] ?? __('The moment enrollment is complete, the next lesson is ready to open.') }}</p>
                </div>
            </div>
            <div class="cf-trust-card">
                <div class="cf-trust-card-media">
                    <img src="{{ $courseCards->get(2)?->thumbnail_url ?? \App\Support\MediaAsset::courseFallback('trust-3') }}" alt="{{ $copy['trust_3_title'] ?? __('Structured student journey') }}" loading="lazy">
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_3_title'] ?? __('Structured student journey') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_3_body'] ?? __('Lessons stay ordered, progress stays visible, and the platform stays easy to follow.') }}</p>
                </div>
            </div>
            <div class="cf-trust-card">
                <div class="cf-trust-card-media">
                    <img src="{{ $courseCards->get(3)?->thumbnail_url ?? \App\Support\MediaAsset::courseFallback('trust-4') }}" alt="{{ $copy['trust_4_title'] ?? __('Trust before purchase') }}" loading="lazy">
                </div>
                <div>
                    <p class="text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['trust_4_title'] ?? __('Trust before purchase') }}</p>
                    <p class="mt-1 text-sm text-[var(--color-text-muted)]">{{ $copy['trust_4_body'] ?? __('A visible instructor, clear pricing, and protected access make the offer feel credible.') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
