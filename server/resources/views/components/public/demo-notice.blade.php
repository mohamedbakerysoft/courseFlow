@php $ar = app()->getLocale() === 'ar'; @endphp
<section aria-label="{{ $ar ? 'ملاحظة النسخة التجريبية' : 'Demo Notice' }}" class="mb-4">
    <div class="rounded-lg border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/10 px-4 py-2">
        <p class="text-xs sm:text-sm text-[var(--color-text-muted)]">
            {{ $ar ? 'أنت تشاهد النسخة التجريبية. بعض الخصائص محدودة لأغراض العرض فقط.' : 'You’re viewing the demo version. Some features are limited for preview purposes.' }}
        </p>
    </div>
</section>
