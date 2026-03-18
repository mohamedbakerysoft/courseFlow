@props(['copy' => []])

<section aria-label="{{ __('How it works') }}" class="cf-section-shell overflow-hidden !px-6 !py-8 sm:!px-8">
    <div class="grid gap-8 lg:grid-cols-[0.9fr,1.1fr] lg:items-center">
        <div class="space-y-4">
            <span class="cf-kicker">{{ $copy['flow_kicker'] ?? __('How it works') }}</span>
            <h2 class="cf-heading">{{ $copy['flow_title'] ?? __('Guide the user from discovery to enrollment to structured learning') }}</h2>
            <p class="cf-subheading">{{ $copy['flow_subtitle'] ?? __('The buying journey and the learning journey should feel like one connected product, not separate screens.') }}</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="cf-panel-soft px-5 py-5">
                <p class="text-3xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">01</p>
                <p class="mt-3 text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['flow_step_1_title'] ?? __('Discover the offer') }}</p>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $copy['flow_step_1_body'] ?? __('A clean landing page and stronger course cards help visitors understand the value quickly.') }}</p>
            </div>
            <div class="cf-panel-soft px-5 py-5">
                <p class="text-3xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">02</p>
                <p class="mt-3 text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['flow_step_2_title'] ?? __('Enroll with confidence') }}</p>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $copy['flow_step_2_body'] ?? __('Pricing, payment choices, and course outcomes stay visible when the user is ready to act.') }}</p>
            </div>
            <div class="cf-panel-soft px-5 py-5">
                <p class="text-3xl font-semibold tracking-[-0.03em] text-[var(--color-text-primary)]">03</p>
                <p class="mt-3 text-sm font-semibold text-[var(--color-text-primary)]">{{ $copy['flow_step_3_title'] ?? __('Start learning fast') }}</p>
                <p class="mt-2 text-sm text-[var(--color-text-muted)]">{{ $copy['flow_step_3_body'] ?? __('Students land in a structured curriculum with visible progress and a clear next lesson.') }}</p>
            </div>
        </div>
    </div>
</section>
