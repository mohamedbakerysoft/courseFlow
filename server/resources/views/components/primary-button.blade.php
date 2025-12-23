<button {{ $attributes->merge(['type' => 'submit', 'class' => 'cf-button-primary']) }}>
    {{ $slot }}
</button>
