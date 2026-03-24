<x-app-layout>
    <div class="cf-admin-shell">
        <div class="space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('FAQ')],
            ]" />
            <div>
                <p class="cf-admin-copy text-sm">{{ __('Content') }}</p>
                <h1 class="cf-admin-heading text-3xl font-bold tracking-[-0.04em] text-[var(--color-text-primary)]">{{ __('FAQ Page') }}</h1>
                <p class="cf-admin-copy mt-2 max-w-3xl">{{ __('Manage the standalone FAQ page heading, subheading, and every visible question-answer item from one place.') }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="cf-status-message mt-6">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 grid gap-6 xl:grid-cols-[0.95fr,1.05fr]">
            <div class="space-y-6">
                <form method="POST" action="{{ route('dashboard.faqs.page') }}" class="cf-admin-form-card">
                    @csrf
                    @method('PUT')
                    <div class="cf-admin-section-header">
                        <h2 class="cf-admin-section-title">{{ __('Page Content') }}</h2>
                        <p class="cf-admin-section-copy">{{ __('These fields control the public FAQ page hero section.') }}</p>
                    </div>
                    <div class="cf-admin-form-grid">
                        <div class="cf-admin-field">
                            <label for="heading">{{ __('Heading') }}</label>
                            <input id="heading" name="heading" type="text" value="{{ old('heading', $faqHeading) }}" class="w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        </div>
                        <div class="cf-admin-field">
                            <label for="subheading">{{ __('Subheading') }}</label>
                            <textarea id="subheading" name="subheading" rows="4" class="w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('subheading', $faqSubheading) }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="cf-button-primary">{{ __('Save FAQ Page') }}</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('dashboard.faqs.store') }}" class="cf-admin-form-card">
                    @csrf
                    <div class="cf-admin-section-header">
                        <h2 class="cf-admin-section-title">{{ __('Add FAQ Item') }}</h2>
                        <p class="cf-admin-section-copy">{{ __('Create a new item that can then be reordered, shown, hidden, edited, or removed.') }}</p>
                    </div>
                    <div class="cf-admin-form-grid">
                        <div class="cf-admin-field">
                            <label for="question">{{ __('Question') }}</label>
                            <input id="question" name="question" type="text" value="{{ old('question') }}" class="w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        </div>
                        <div class="cf-admin-field">
                            <label for="answer">{{ __('Answer') }}</label>
                            <textarea id="answer" name="answer" rows="5" class="w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old('answer') }}</textarea>
                        </div>
                        <label class="flex items-center gap-3 rounded-[5px] border border-[var(--color-secondary)]/10 px-4 py-4">
                            <input type="hidden" name="is_visible" value="0">
                            <input type="checkbox" name="is_visible" value="1" class="rounded-[5px] border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" checked>
                            <span class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Visible on public FAQ page') }}</span>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="cf-button-primary">{{ __('Add FAQ Item') }}</button>
                    </div>
                </form>
            </div>

            <section class="cf-admin-form-card" data-faq-manager data-faq-reorder-url="{{ route('dashboard.faqs.reorder') }}">
                <div class="cf-admin-section-header">
                    <h2 class="cf-admin-section-title">{{ __('FAQ Items') }}</h2>
                    <p class="cf-admin-section-copy">{{ __('Drag items to reorder them. Hidden items stay in admin but do not appear on the public FAQ page.') }}</p>
                </div>

                <div data-faq-manager-status class="text-sm text-[var(--color-text-muted)]">{{ __('Ready to reorder FAQ items.') }}</div>

                <div class="space-y-4" data-faq-sorter-list>
                    @forelse ($faqItems as $index => $faq)
                        <div class="rounded-[5px] border border-[rgba(11,11,11,0.08)] bg-white p-4 shadow-[0_10px_24px_rgba(11,11,11,0.03)]" data-faq-item data-faq-id="{{ $faq->id }}">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <button type="button" draggable="true" class="inline-flex h-11 w-11 items-center justify-center rounded-[5px] border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 text-lg text-[var(--color-text-muted)] cursor-grab active:cursor-grabbing" title="{{ __('Drag FAQ item') }}" data-faq-drag-handle>
                                        <span aria-hidden="true">⋮⋮</span>
                                    </button>
                                    <div>
                                        <p class="text-sm font-semibold text-[var(--color-text-primary)]" data-faq-order-badge>{{ __('Item :number', ['number' => $index + 1]) }}</p>
                                        <p class="text-xs text-[var(--color-text-muted)]">{{ $faq->is_visible ? __('Visible') : __('Hidden') }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('dashboard.faqs.destroy', $faq) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cf-button-ghost !px-4 !py-2">{{ __('Delete') }}</button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('dashboard.faqs.update', $faq) }}" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="cf-admin-field">
                                    <label>{{ __('Question') }}</label>
                                    <input type="text" name="question" value="{{ old("question.{$faq->id}", $faq->question) }}" class="w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                                </div>
                                <div class="cf-admin-field">
                                    <label>{{ __('Answer') }}</label>
                                    <textarea name="answer" rows="4" class="w-full rounded-[5px] border-[var(--color-secondary)]/30 shadow-sm focus:border-[var(--color-primary)] focus:ring-[var(--color-primary)]">{{ old("answer.{$faq->id}", $faq->answer) }}</textarea>
                                </div>
                                <label class="flex items-center gap-3 rounded-[5px] border border-[var(--color-secondary)]/10 px-4 py-4">
                                    <input type="hidden" name="is_visible" value="0">
                                    <input type="checkbox" name="is_visible" value="1" class="rounded-[5px] border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked($faq->is_visible)>
                                    <span class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Visible on public FAQ page') }}</span>
                                </label>
                                <div class="flex justify-end">
                                    <button type="submit" class="cf-button-primary !px-4 !py-2">{{ __('Save Item') }}</button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <div class="cf-panel px-8 py-10 text-center text-[var(--color-text-muted)]">
                            {{ __('No FAQ items yet.') }}
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
