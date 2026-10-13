<x-guest-layout>
    <div class="w-full max-w-md mx-auto animate-in">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-premium shadow-lift mb-4 ring-1 ring-white/10">
                <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="SMK Pesat IT XPro" class="w-14 h-14 object-contain">
            </div>
            <h1 class="text-3xl font-extrabold text-brand-900 dark:text-ink-50 tracking-tight">
                Selamat Datang
            </h1>
            <p class="mt-2 text-sm text-ink-500 dark:text-ink-400">
                Sistem Administrasi Surat <span class="font-semibold text-gold-600">SMK Pesat IT XPro</span>
            </p>
        </div>

        <div class="card p-8">

            @if (session('status'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="label label-required">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input pl-12 @error('email') border-red-400 @enderror" placeholder="nama@smkpesatpro.sch.id">
                    </div>
                    @error('email')
                        <p class="error-text">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label label-required">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="input pl-12 @error('password') border-red-400 @enderror" placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded-md border-ink-300 text-gold-500 focus:ring-gold-400">
                        <span class="text-ink-600 dark:text-ink-300">Ingat Saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand-700 dark:text-gold-400 hover:underline">
                            Lupa Kata Sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-primary w-full btn-lg">
                    Masuk Ke Sistem
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            @if(config('app.env') === 'local')
                <div class="mt-6 pt-6 border-t border-ink-100 dark:border-brand-800">
                    <p class="text-[10px] text-center text-ink-400 uppercase tracking-widest font-bold mb-3">
                        Quick Login (Demo)
                    </p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="quickLogin('aesa@simas.test')" class="px-3 py-2.5 rounded-xl bg-brand-50 hover:bg-brand-100 dark:bg-brand-900/50 dark:hover:bg-brand-900 text-brand-700 dark:text-ink-100 text-xs font-semibold transition">
                            Admin TU
                        </button>
                        <button type="button" onclick="quickLogin('kepsek@simas.test')" class="px-3 py-2.5 rounded-xl bg-gold-50 hover:bg-gold-100 dark:bg-gold-900/30 dark:hover:bg-gold-900/50 text-gold-800 dark:text-gold-300 text-xs font-semibold transition">
                            Pimpinan
                        </button>
                        <button type="button" onclick="quickLogin('ahmad@simas.test')" class="px-3 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-semibold transition">
                            Guru
                        </button>
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
        </div>

        <p class="text-center text-xs text-ink-400 mt-6">
            &copy; {{ date('Y') }} SMK Pesat IT XPro · IT Expert & Professional
        </p>
    </div>
</x-guest-layout>
