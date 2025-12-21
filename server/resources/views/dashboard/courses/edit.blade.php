<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--color-text-primary)] leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto">
        <x-breadcrumbs :items="[
            ['label' => __('Dashboard'), 'url' => route('dashboard')],
            ['label' => __('Courses'), 'url' => route('dashboard.courses.index')],
            ['label' => __('Edit')]
        ]" />
        <div class="mb-4">
            <a href="{{ route('dashboard.courses.index') }}" class="text-[var(--color-primary)] hover:underline">Back to Courses</a>
        </div>
        <div class="mb-4 text-sm text-[var(--color-text-muted)]">
            Status: <span class="font-medium">{{ ucfirst($course->status) }}</span>
        </div>
        <form x-data="{isSubmitting:false, thumbPreview:null}" x-on:submit="isSubmitting=true" method="POST" action="{{ route('dashboard.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input name="title" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->title }}" required>
                @error('title')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Slug</label>
                <input name="slug" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->slug }}" required>
                <p class="text-xs text-[var(--color-text-muted)] mt-1">Only letters, numbers, and dashes.</p>
                @error('slug')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 w-full border rounded p-2">{{ $course->description }}</textarea>
                @error('description')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Thumbnail Image</label>
                    <input name="thumbnail" type="file" accept="image/*" class="mt-1 w-full border rounded p-2" x-on:change="thumbPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                    <p class="text-xs text-[var(--color-text-muted)] mt-1">Upload a new image to update the thumbnail.</p>
                    @error('thumbnail')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                    <div class="mt-2">
                        @if ($course->thumbnail_path)
                            <img src="{{ asset($course->thumbnail_path) }}" alt="Current thumbnail" class="w-40 h-24 object-cover rounded border border-[var(--color-secondary)]/20" x-show="!thumbPreview">
                        @endif
                        <template x-if="thumbPreview">
                            <img :src="thumbPreview" alt="Thumbnail preview" class="w-40 h-24 object-cover rounded border border-[var(--color-secondary)]/20">
                        </template>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Language</label>
                    <input name="language" type="text" class="mt-1 w-full border rounded p-2" value="{{ $course->language }}">
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
                    <input name="price" type="number" step="0.01" class="mt-1 w-full border rounded p-2" value="{{ $course->price }}">
                    <p class="text-xs text-[var(--color-text-muted)] mt-1">Set 0 for free courses.</p>
                    @error('price')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">Currency</label>
                    <select name="currency" class="mt-1 w-full border rounded p-2">
                        @foreach($supportedCurrencies as $code)
                            <option value="{{ $code }}" @selected(old('currency', $course->currency ?? $defaultCurrency) === $code)>{{ $code }}</option>
                        @endforeach
                    </select>
                    @error('currency')<p class="text-[var(--color-error)] text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center">
                    <input id="is_free" name="is_free" type="checkbox" value="1" class="mr-2" {{ $course->is_free ? 'checked' : '' }}>
                    <label for="is_free" class="text-sm">Is Free</label>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" :disabled="isSubmitting" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Save</button>
            </div>
        </form>
        <div class="mt-4 flex items-center space-x-4">
            @if($course->status === \App\Models\Course::STATUS_DRAFT)
            <form action="{{ route('dashboard.courses.publish', $course) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-accent)] text-white text-sm font-semibold shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-accent)]">Publish</button>
            </form>
            @else
            <form action="{{ route('dashboard.courses.unpublish', $course) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-secondary)] text-white text-sm font-semibold shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-secondary)]">Unpublish</button>
            </form>
            @endif
        </div>
        <div class="mt-6">
            <a href="{{ route('dashboard.courses.lessons.index', $course) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">Manage Lessons</a>
        </div>
    </div>
</x-app-layout>
