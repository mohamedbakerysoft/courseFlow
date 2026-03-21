@php
    $enabled = (bool) ($enabled ?? false);
    $provider = (string) ($provider ?? 'none');
@endphp

@if ($enabled && $provider === 'tawk')
    <button
        type="button"
        class="fixed bottom-6 right-6 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[var(--color-primary)] text-white shadow-[0_18px_40px_rgba(11,11,11,0.2)] transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)] sm:bottom-6 sm:right-6"
        aria-label="{{ __('Open live chat') }}"
        onclick="if (window.Tawk_API && typeof window.Tawk_API.maximize === 'function') { window.Tawk_API.maximize(); }"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-7 6 2.6-2H19a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h1Z"/>
        </svg>
    </button>
@endif
