@php $ar = app()->getLocale() === 'ar'; @endphp

<section aria-label="{{ $ar ? 'ملاحظة النسخة التجريبية' : 'Demo notice' }}">
    <div class="cf-panel-soft px-4 py-4">
        <p class="text-sm text-[var(--color-text-muted)]">
            {{ $ar ? 'أنت تشاهد عرضاً تجريبياً. بعض خيارات الدفع أو التخصيص قد تكون مقيّدة لأغراض المعاينة فقط.' : 'You are viewing a demo environment. Some payment or customization options may be limited for preview purposes only.' }}
        </p>
    </div>
</section>
