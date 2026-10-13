<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if(config('app.env') === 'local')
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <p class="text-xs text-center text-gray-500 mb-3">Quick Login (Demo)</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="quickLogin('aesa@simas.test')" class="px-3 py-2 text-xs rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium transition">
                        👩‍💼 Admin TU
                    </button>
                    <button type="button" onclick="quickLogin('kepsek@simas.test')" class="px-3 py-2 text-xs rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium transition">
                        👨‍💼 Pimpinan
                    </button>
                    <button type="button" onclick="quickLogin('ahmad@simas.test')" class="px-3 py-2 text-xs rounded-lg bg-green-50 hover:bg-green-100 text-green-700 font-medium transition">
                        👨‍🏫 Guru
                    </button>
                </div>
            </div>
        </div>

        <script>
            function quickLogin(email) {
                document.querySelector('input[name="email"]').value = email;
                document.querySelector('input[name="password"]').value = 'password';
                document.querySelector('form').submit();
            }
        </script>
    @endif
</x-guest-layout>
