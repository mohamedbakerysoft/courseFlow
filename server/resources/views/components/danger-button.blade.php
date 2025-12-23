<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-[var(--color-accent)] px-5 py-3 text-sm font-semibold text-white shadow-[0_18px_38px_rgba(249,115,22,0.22)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_22px_44px_rgba(249,115,22,0.26)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)] focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
