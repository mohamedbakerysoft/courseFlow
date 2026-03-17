<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full border border-[var(--color-error)] bg-[var(--color-error)] px-5 py-3 text-sm font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[var(--color-error)] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
