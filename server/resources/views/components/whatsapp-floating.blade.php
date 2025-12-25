@php
    $enabled = (bool) ($enabled ?? false);
    $phoneRaw = (string) ($phone ?? '');
    $messageRaw = (string) ($message ?? '');
    $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
    $message = trim($messageRaw !== '' ? $messageRaw : 'Hello! I have a question about your courses.');
    $link = $phone !== '' ? ('https://wa.me/'.$phone.'?text='.urlencode($message)) : '';
@endphp
@if ($enabled && $phone !== '')
    <a href="{{ $link }}"
       class="fixed bottom-6 right-6 inline-flex items-center gap-2 px-4 py-3 rounded-full shadow-lg bg-[#25D366] text-white text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#25D366]"
       aria-label="{{ __('WhatsApp') }}"
       target="_blank"
       rel="noopener">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.94 14.5L2 22l5.62-1.48A10 10 0 1 0 12 2zm5.2 14.24c-.22.62-1.28 1.18-1.78 1.23-.46.05-1.05.08-2.58-.54-2.16-.89-3.55-3.08-3.66-3.22-.1-.15-.87-1.15-.87-2.2 0-1.05.55-1.56.75-1.78.2-.22.48-.28.64-.28h.46c.15 0 .36-.06.55.41.22.54.74 1.86.81 1.99.07.13.12.3.02.48-.09.17-.15.27-.29.42-.15.16-.31.35-.45.47-.15.12-.3.26-.13.52.17.27.76 1.23 1.65 1.99 1.14.94 2.1 1.23 2.39 1.37.29.14.46.12.63-.07.17-.2.72-.84.91-1.13.19-.29.39-.24.63-.14.24.1 1.51.71 1.77.84.26.13.43.19.5.3.06.11.06.64-.17 1.26z"/></svg>
        <span class="hidden sm:inline">{{ __('WhatsApp') }}</span>
    </a>
@endif
