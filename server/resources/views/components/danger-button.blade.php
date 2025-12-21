<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold bg-[var(--color-accent)] text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-accent)] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
