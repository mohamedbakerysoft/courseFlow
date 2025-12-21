<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border rounded-md font-semibold text-xs text-[var(--color-text-primary)] uppercase tracking-widest shadow-sm border-[var(--color-secondary)]/30 hover:bg-[var(--color-secondary)]/10 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
