<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Create Book') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Books'), 'url' => route('dashboard.books.index')],
            ['label' => __('Create')]
        ]" />

        <form x-data="{thumbPreview:null}" method="POST" action="{{ route('dashboard.books.store') }}" enctype="multipart/form-data" class="space-y-6 cf-admin-form-card">
            @csrf
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Book title') }}</label>
                    <input name="title" type="text" class="cf-input mt-2" value="{{ old('title') }}" required>
                    @error('title')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Slug') }}</label>
                    <input name="slug" type="text" class="cf-input mt-2" value="{{ old('slug') }}" required>
                    @error('slug')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Language') }}</label>
                    <input name="language" type="text" class="cf-input mt-2" value="{{ old('language', 'en') }}">
                    @error('language')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Description') }}</label>
                    <textarea name="description" rows="6" class="cf-input mt-2">{{ old('description') }}</textarea>
                    @error('description')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Thumbnail image') }}</label>
                    <input name="thumbnail" type="file" accept="image/*" class="cf-input mt-2" x-on:change="thumbPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                    @error('thumbnail')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                    <template x-if="thumbPreview">
                        <img :src="thumbPreview" alt="Thumbnail preview" class="mt-3 h-32 w-52 rounded-2xl object-cover border border-[var(--color-secondary)]/10">
                    </template>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Download file') }}</label>
                    <input name="download_file" type="file" accept=".pdf,.epub,.zip,.doc,.docx" class="cf-input mt-2">
                    <p class="mt-2 text-xs text-[var(--color-text-muted)]">{{ __('Accepted formats: PDF, EPUB, ZIP, DOC, DOCX.') }}</p>
                    @error('download_file')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Price') }}</label>
                    <input name="price" type="number" step="0.01" class="cf-input mt-2" value="{{ old('price', 0) }}">
                    @error('price')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Currency') }}</label>
                    <input name="currency" type="text" class="cf-input mt-2" value="{{ old('currency', 'USD') }}">
                    @error('currency')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-3 rounded-2xl border border-[var(--color-secondary)]/10 px-4 py-4">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked(old('is_free', true))>
                    <span class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Free download') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('dashboard.books.index') }}" class="cf-button-secondary">{{ __('Cancel') }}</a>
                <button type="submit" class="cf-button-primary">{{ __('Create Book') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
