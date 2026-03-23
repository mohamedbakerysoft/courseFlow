@props([
    'name',
    'value' => '',
    'label' => null,
    'rows' => 14,
])

<div class="space-y-2" data-rich-editor data-image-upload-url="{{ route('dashboard.rich_text.images.store') }}">
    @if ($label)
        <label class="block text-sm font-medium">{{ $label }}</label>
    @endif

    <div class="flex flex-wrap gap-2 rounded-t-lg border border-b-0 border-[var(--color-secondary)]/20 bg-[var(--color-accent)]/40 px-3 py-3">
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-command="bold">{{ __('Bold') }}</button>
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-command="italic">{{ __('Italic') }}</button>
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-command="insertUnorderedList">{{ __('Bullet List') }}</button>
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-block="h2">{{ __('Heading') }}</button>
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-block="p">{{ __('Paragraph') }}</button>
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-link>{{ __('Link') }}</button>
        <button type="button" class="rounded border border-[var(--color-secondary)]/20 px-3 py-1.5 text-sm" data-editor-image>{{ __('Image') }}</button>
    </div>

    <div
        class="min-h-[18rem] rounded-b-lg border border-[var(--color-secondary)]/20 bg-white px-4 py-3 text-sm leading-7 focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]"
        contenteditable="true"
        data-editor-surface
    >{!! old($name, $value) !!}</div>

    <textarea name="{{ $name }}" rows="{{ $rows }}" class="hidden" data-editor-input>{!! old($name, $value) !!}</textarea>
    <input type="file" accept="image/*" class="hidden" data-editor-image-input>
    <p class="text-xs text-[var(--color-text-muted)]">{{ __('You can format text, add links, and upload images directly into the content.') }}</p>
</div>
