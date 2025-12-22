<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Create Course') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <div class="mb-4 rounded-lg border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 px-4 py-3 text-xs text-[var(--color-text-muted)]">
            {{ __('You’ve explored the core workflow. The full version is designed for real students and real revenue.') }}
        </div>
        <x-public.demo-notice />
        <form x-data="{isSubmitting:false, thumbPreview:null}" x-on:submit="isSubmitting=true" method="POST" action="{{ route('dashboard.courses.store') }}" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            <p class="text-xs text-[var(--color-text-muted)]">
                {{ app()->getLocale() === 'ar' ? 'بعض الخصائص المتقدمة غير مفعلة في النسخة التجريبية.' : 'Some advanced features are disabled in demo mode.' }}
            </p>
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" required>
                @error('title')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" required>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">Only letters, numbers, and dashes.</p>
                @error('slug')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 w-full border rounded p-2"></textarea>
                @error('description')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Thumbnail Image</label>
                    <input name="thumbnail" type="file" accept="image/*" class="mt-1 w-full border rounded p-2" x-on:change="thumbPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                    <p class="text-xs text-[var(--color-text-muted)] mt-1">Upload an image; it will be stored and referenced automatically.</p>
                    @error('thumbnail')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    <div class="mt-2" x-show="thumbPreview">
                        <img :src="thumbPreview" alt="Thumbnail preview" class="w-40 h-24 object-cover rounded border border-[var(--color-secondary)]/20">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Language</label>
                    <input name="language" type="text" value="en" class="mt-1 w-full border rounded p-2">
                    @error('language')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            @inject('settings', 'App\Services\SettingsService')
            @php
                $supportedCurrencies = config('currencies.supported', ['USD']);
                $defaultCurrency = $settings->get('payments.default_currency', 'USD');
            @endphp
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input name="price" type="number" step="0.01" value="0" class="mt-1 w-full border rounded p-2">
                    <p class="text-xs text-[var(--color-text-muted)] mt-1">Set 0 for free courses.</p>
                    @error('price')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Currency</label>
                    <select name="currency" class="mt-1 w-full border rounded p-2">
                        @foreach($supportedCurrencies as $code)
                            <option value="{{ $code }}" @selected(old('currency', $defaultCurrency) === $code)>{{ $code }}</option>
                        @endforeach
                    </select>
                    @error('currency')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="mr-2" checked>
                    <label for="is_free" class="text-sm">Is Free</label>
                </div>
            </div>
            <div>
                <button type="submit" :disabled="isSubmitting" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
