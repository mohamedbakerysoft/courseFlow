@props(['course', 'ctaLabel' => 'View course', 'ctaUrl' => null])

@php
    $ctaUrl = $ctaUrl ?? route('courses.show', $course);
    $thumb = $course->thumbnail_url;
    $thumbFallback = $course->thumbnail_fallback_url;
    $instructorName = $course->instructor?->name ?? __('Instructor');
    $instructorAvatar = $course->instructor?->profile_image_url ?? \App\Support\MediaAsset::avatarFallback($instructorName);
    $instructorAvatarFallback = $course->instructor?->profile_image_fallback_url ?? \App\Support\MediaAsset::avatarFallback($instructorName);
    $price = $course->is_free || (float) $course->price == 0.0
        ? __('Free')
        : number_format((float) $course->price, 2).' '.$course->currency;
    $lessonsCount = (int) ($course->lessons_count ?? 0);
    $lessonLabel = app()->getLocale() === 'ar'
        ? 'دروس'
        : Str::plural('lesson', $lessonsCount);
    $language = strtoupper($course->language ?? 'EN');
@endphp

<article class="cf-course-card group">
    <a href="{{ $ctaUrl }}" class="flex h-full flex-col">
        <div class="cf-course-media">
            <img
                src="{{ $thumb }}"
                alt="{{ $course->title }}"
                class="aspect-[16/10] w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                loading="lazy"
                onerror="this.onerror=null;this.src='{{ $thumbFallback }}';"
            >
            <div class="cf-course-overlay"></div>
            <div class="absolute inset-x-0 top-0 flex items-start justify-between p-4">
                <span class="cf-price-pill">
                    {{ $price }}
                </span>
                <span class="rounded-full border border-white/18 bg-white/88 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-[var(--color-text-primary)]">
                    {{ $language }}
                </span>
            </div>
        </div>

        <div class="cf-course-content">
            <div class="cf-course-instructor">
                <img
                    src="{{ $instructorAvatar }}"
                    alt="{{ $instructorName }}"
                    class="h-12 w-12 rounded-2xl object-cover ring-2 ring-[rgba(193,18,31,0.08)]"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='{{ $instructorAvatarFallback }}';"
                >
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-[var(--color-text-primary)]">{{ $instructorName }}</p>
                    <p class="truncate text-xs text-[var(--color-text-muted)]">{{ __('Instructor-led learning') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    @if (isset($course->lessons_count))
                        <span class="cf-chip !py-1 !text-[11px] !font-semibold">
                            {{ $lessonsCount }} {{ $lessonLabel }}
                        </span>
                    @endif
                    <span class="cf-chip !py-1 !text-[11px] !font-semibold">{{ __('Instant access') }}</span>
                </div>
                <h3 class="text-[1.55rem] font-bold leading-[1.15] tracking-[-0.045em] text-[var(--color-text-primary)] line-clamp-2">
                    {{ $course->title }}
                </h3>
                @if (!empty($course->description))
                    <p class="text-[15px] leading-7 text-[var(--color-text-muted)] line-clamp-3">
                        {{ str($course->description)->limit(158) }}
                    </p>
                @endif
            </div>

            <div class="cf-course-meta">
                <span class="cf-course-meta-pill">{{ __('Lifetime access') }}</span>
                <span class="cf-course-meta-pill">{{ __('Secure checkout') }}</span>
                <span class="cf-course-meta-pill">{{ __('Self-paced') }}</span>
            </div>

            <div class="cf-course-footer">
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[var(--color-primary)]">
                        {{ __('Ready to start') }}
                    </p>
                    <p class="cf-course-summary">
                        {{ __('Clear pricing, structured lessons, and a direct path into the course.') }}
                    </p>
                </div>
                <span class="cf-course-cta">{{ __($ctaLabel) }}</span>
            </div>
        </div>
    </a>
</article>
