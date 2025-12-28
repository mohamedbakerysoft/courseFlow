<x-app-layout>
    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="updatesPage({
        detectUrl: '{{ route('dashboard.settings.updates.detect') }}',
        runUrl: '{{ route('dashboard.settings.updates.run') }}',
        csrf: '{{ csrf_token() }}'
    })">
        <div class="mb-6 space-y-3">
            <x-breadcrumbs :items="[
                ['label' => __('Dashboard'), 'url' => route('dashboard')],
                ['label' => __('Settings'), 'url' => route('dashboard.settings.edit')],
                ['label' => __('Updates')],
            ]" />
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[var(--color-text-primary)]">
                    {{ __('Updates') }}
                </h1>
                <a href="{{ route('dashboard.settings.edit') }}" class="inline-flex items-center text-sm font-medium text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)]">
                    {{ __('Back to Settings') }}
                </a>
            </div>
        </div>

        <section class="mb-6 rounded-lg border border-[var(--color-secondary)]/10 bg-white p-6">
            <h2 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Update Checklist') }}</h2>
            <ol class="list-decimal ml-5 space-y-2 text-sm text-[var(--color-text-primary)]">
                <li>{{ __('Download the latest version from CodeCanyon') }}</li>
                <li>{{ __('Upload and replace files on your server (EXCEPT: .env, storage/, uploads/)') }}</li>
                <li>{{ __('Make sure file upload is complete') }}</li>
                <li>{{ __('Click the button below') }}</li>
            </ol>
            <div class="mt-4">
                <button type="button" x-on:click="confirmUploaded()" class="inline-flex items-center px-4 py-2 rounded-md bg-[var(--color-primary)] text-white text-sm font-semibold shadow-sm hover:bg-[var(--color-primary-hover)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
                    {{ __('I Have Uploaded the New Version') }}
                </button>
            </div>
        </section>

        <section class="mb-6 rounded-lg border border-[var(--color-secondary)]/10 bg-white p-6" x-show="detected">
            <h2 class="text-lg font-semibold text-[var(--color-text-primary)] mb-3">{{ __('Version Detection') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-[var(--color-text-muted)]">{{ __('Current installed version') }}</p>
                    <p class="text-base font-semibold text-[var(--color-text-primary)]" x-text="currentVersion ?? '{{ $currentVersion }}'"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-[var(--color-text-muted)]">{{ __('New version detected') }}</p>
                    <p class="text-base font-semibold" :class="updateAvailable ? 'text-[var(--color-primary)]' : 'text-[var(--color-text-muted)]'" x-text="newVersion ?? '-'"></p>
                </div>
            </div>
            <div class="mt-4">
                <template x-if="!updateAvailable">
                    <div class="rounded-md border border-[var(--color-secondary)]/20 bg-[var(--color-secondary)]/5 p-3 text-sm text-[var(--color-text-primary)]">
                        {{ __('No update detected') }}
                    </div>
                </template>
                <template x-if="updateAvailable">
                    <div class="rounded-md border border-[var(--color-primary)]/20 bg-[var(--color-primary)]/10 p-3 text-sm text-[var(--color-primary)]">
                        {{ __('New update available') }}
                    </div>
                </template>
            </div>
            <div class="mt-4">
                <button type="button" x-on:click="runUpdate()" :disabled="!updateAvailable || running"
                        class="inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2"
                        :class="updateAvailable && !running ? 'bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-hover)] focus:ring-[var(--color-primary)]' : 'bg-white text-[var(--color-text-muted)] border border-[var(--color-secondary)]/30 cursor-not-allowed'">
                    <span x-show="!running">{{ __('Run Update') }}</span>
                    <span x-show="running">{{ __('Updating...') }}</span>
                </button>
            </div>
            <div class="mt-4" x-show="message">
                <div class="rounded-md border p-3 text-sm"
                     :class="ok ? 'border-[var(--color-primary)]/30 bg-[var(--color-primary)]/10 text-[var(--color-primary)]' : 'border-[var(--color-error)]/30 bg-[var(--color-error)]/10 text-[var(--color-error)]'">
                    <p x-text="message"></p>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-[var(--color-accent)]/20 bg-[var(--color-accent)]/10 p-4">
            <h3 class="text-sm font-semibold text-[var(--color-accent)] mb-1">{{ __('WARNING') }}</h3>
            <p class="text-sm text-[var(--color-text-primary)]">
                {{ __('If the site becomes inaccessible after uploading files, connect to your server via SSH and run:') }}
            </p>
            <div class="mt-2">
                <code class="text-xs px-2 py-1 rounded bg-white border border-[var(--color-secondary)]/30 text-[var(--color-text-primary)]">bash update.sh</code>
            </div>
            <p class="text-xs text-[var(--color-text-muted)] mt-2">
                {{ __('This will safely complete the update.') }}
            </p>
        </section>
    </div>

    <script>
        function updatesPage(opts = {}) {
            return {
                detected: false,
                updateAvailable: false,
                currentVersion: '{{ $currentVersion }}',
                newVersion: null,
                running: false,
                ok: false,
                message: '',
                detectUrl: opts.detectUrl || '',
                runUrl: opts.runUrl || '',
                csrf: opts.csrf || '',
                async confirmUploaded() {
                    try {
                        const res = await fetch(this.detectUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        this.detected = true;
                        this.currentVersion = data.current_version;
                        this.newVersion = data.new_version;
                        this.updateAvailable = !!data.update_available;
                    } catch (e) {
                        this.detected = true;
                        this.updateAvailable = false;
                        this.message = 'Version detection failed.';
                        this.ok = false;
                    }
                },
                async runUpdate() {
                    if (!this.updateAvailable) return;
                    this.running = true;
                    this.message = '';
                    try {
                        const res = await fetch(this.runUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrf,
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();
                        this.ok = !!data.ok;
                        this.message = data.message || '';
                        if (data.ok && data.new_version) {
                            this.currentVersion = data.new_version;
                            this.newVersion = data.new_version;
                            this.updateAvailable = false;
                        }
                    } catch (e) {
                        this.ok = false;
                        this.message = 'Update failed.';
                    } finally {
                        this.running = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
