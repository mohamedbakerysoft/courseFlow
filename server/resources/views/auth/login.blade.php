<x-guest-layout>
    <div x-data="{ fill(email, password) { $refs.email.value = email; $refs.password.value = password } }">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input x-ref="email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input x-ref="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    @if(app()->environment(['local','dusk','dusk.local']))
        <div class="mt-6 bg-white border border-gray-200 rounded p-4">
            <p class="text-sm font-medium text-gray-800 mb-3">Demo Login (Local / Testing Only)</p>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Admin — admin@example.com</span>
                    <button type="button" class="inline-flex items-center px-3 py-1 rounded bg-gray-800 text-white hover:bg-gray-700 focus:ring-2 focus:ring-indigo-500"
                            x-on:click="fill('admin@example.com','password')">Fill</button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Instructor — instructor@demo.com</span>
                    <button type="button" class="inline-flex items-center px-3 py-1 rounded bg-gray-800 text-white hover:bg-gray-700 focus:ring-2 focus:ring-indigo-500"
                            x-on:click="fill('instructor@demo.com','password')">Fill</button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Student — student@demo.com</span>
                    <button type="button" class="inline-flex items-center px-3 py-1 rounded bg-gray-800 text-white hover:bg-gray-700 focus:ring-2 focus:ring-indigo-500"
                            x-on:click="fill('student@demo.com','password')">Fill</button>
                </div>
            </div>
        </div>
    @endif
    </div>
</x-guest-layout>
