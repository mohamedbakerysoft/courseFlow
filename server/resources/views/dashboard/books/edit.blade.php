<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Edit Book') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Books'), 'url' => route('dashboard.books.index')],
            ['label' => __('Edit')]
        ]" />

        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-[var(--color-text-muted)]">{{ __('Status') }}: <span class="font-medium text-[var(--color-text-primary)]">{{ ucfirst($book->status) }}</span></p>
            <a href="{{ route('books.show', $book) }}" class="cf-button-secondary !px-4 !py-2">{{ __('Preview public page') }}</a>
        </div>

        <form x-data="{thumbPreview:null}" method="POST" action="{{ route('dashboard.books.update', $book) }}" enctype="multipart/form-data" class="space-y-6 cf-admin-form-card">
            @csrf
            @method('PUT')
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Book title') }}</label>
                    <input name="title" type="text" class="cf-input mt-2" value="{{ old('title', $book->title) }}" required>
                    @error('title')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Slug') }}</label>
                    <input name="slug" type="text" class="cf-input mt-2" value="{{ old('slug', $book->slug) }}" required>
                    @error('slug')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Language') }}</label>
                    <select name="language" class="cf-input mt-2">
                        @foreach($languageOptions as $languageOption)
                            <option value="{{ $languageOption->code }}" @selected(old('language', $book->language ?: 'en') === $languageOption->code)>{{ $languageOption->label }}</option>
                        @endforeach
                    </select>
                    @error('language')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Description') }}</label>
                    <textarea name="description" rows="6" class="cf-input mt-2">{{ old('description', $book->description) }}</textarea>
                    @error('description')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Thumbnail image') }}</label>
                    <input name="thumbnail" type="file" accept="image/*" class="cf-input mt-2" x-on:change="thumbPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                    @error('thumbnail')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                    <div class="mt-3">
                        <img x-show="thumbPreview" :src="thumbPreview" alt="Thumbnail preview" class="h-32 w-52 rounded-2xl object-cover border border-[var(--color-secondary)]/10">
                        <img x-show="!thumbPreview" src="{{ $book->thumbnail_url }}" alt="{{ $book->title }}" class="h-32 w-52 rounded-2xl object-cover border border-[var(--color-secondary)]/10" onerror="this.onerror=null;this.src='{{ $book->thumbnail_fallback_url }}';">
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Download file') }}</label>
                        <input name="download_file" type="file" accept=".pdf,.epub,.zip,.doc,.docx" class="cf-input mt-2">
                        @error('download_file')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                        @if ($book->download_file_path)
                            <p class="mt-2 text-xs text-[var(--color-text-muted)]">{{ __('Current file') }}: <span class="font-medium text-[var(--color-text-primary)]">{{ basename($book->download_file_path) }}</span></p>
                        @endif
                    </div>
                    <label class="flex items-center gap-3 rounded-2xl border border-[var(--color-secondary)]/10 px-4 py-4">
                        <input name="remove_download_file" type="checkbox" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]">
                        <span class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Remove current download file') }}</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Price') }}</label>
                    <input name="price" type="number" step="0.01" class="cf-input mt-2" value="{{ old('price', $book->price) }}">
                    @error('price')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--color-text-muted)]">{{ __('Currency') }}</label>
                    <select name="currency" class="cf-input mt-2">
                        @foreach($currencyOptions as $currencyOption)
                            <option value="{{ $currencyOption->code }}" @selected(old('currency', $book->currency ?: 'USD') === $currencyOption->code)>{{ $currencyOption->label }}</option>
                        @endforeach
                    </select>
                    @error('currency')<p class="text-[var(--color-error)] text-sm mt-2">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-3 rounded-2xl border border-[var(--color-secondary)]/10 px-4 py-4">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="rounded border-[var(--color-secondary)]/30 text-[var(--color-primary)] focus:ring-[var(--color-primary)]" @checked(old('is_free', $book->is_free))>
                    <span class="text-sm font-medium text-[var(--color-text-primary)]">{{ __('Free download') }}</span>
                </label>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('dashboard.books.index') }}" class="cf-button-secondary">{{ __('Back') }}</a>
                <button type="submit" class="cf-button-primary">{{ __('Save Book') }}</button>
            </div>
        </form>

        <div class="mt-4 flex items-center gap-3">
            @if($book->status === \App\Models\Course::STATUS_DRAFT)
                <form action="{{ route('dashboard.books.publish', $book) }}" method="POST">
                    @csrf
                    <button type="submit" class="cf-button-primary">{{ __('Publish') }}</button>
                </form>
            @else
                <form action="{{ route('dashboard.books.unpublish', $book) }}" method="POST">
                    @csrf
                    <button type="submit" class="cf-button-secondary">{{ __('Unpublish') }}</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
