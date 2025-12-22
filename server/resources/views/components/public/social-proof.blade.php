@php $ar = app()->getLocale() === 'ar'; @endphp
<section aria-label="{{ $ar ? 'دليل اجتماعي' : 'Social Proof' }}" class="space-y-8">
    <div class="space-y-3 text-center">
        <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--color-text-primary)]">
            {{ $ar ? 'مستخدمة من قبل مدربين مستقلين حول العالم' : 'Used by independent instructors worldwide' }}
        </h2>
        <p class="text-sm sm:text-base text-[var(--color-text-muted)] max-w-2xl mx-auto">
            {{ $ar ? 'مصممة للمبدعين والمدربين والمعلمين' : 'Built for creators, coaches, and educators' }}
        </p>
    </div>
    <div class="flex items-center justify-center -space-x-2 rtl:space-x-reverse">
        <span class="inline-flex h-8 w-8 rounded-full bg-[var(--color-primary)]/10 ring-2 ring-white"></span>
        <span class="inline-flex h-8 w-8 rounded-full bg-[var(--color-secondary)]/10 ring-2 ring-white"></span>
        <span class="inline-flex h-8 w-8 rounded-full bg-[var(--color-accent)]/10 ring-2 ring-white"></span>
        <span class="inline-flex h-8 w-8 rounded-full bg-[var(--color-primary)]/20 ring-2 ring-white"></span>
        <span class="inline-flex h-8 w-8 rounded-full bg-[var(--color-secondary)]/20 ring-2 ring-white"></span>
    </div>
</section>
