<x-guest-layout>
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-gradient-premium shadow-lift mb-4 ring-1 ring-white/10">
            <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        </div>
        <h1 class="text-2xl font-extrabold text-brand-900 dark:text-ink-50 tracking-tight">
            Buat Akun Baru
        </h1>
        <p class="mt-2 text-sm text-ink-500 dark:text-ink-400">
            Bergabung Dengan SIMAS SMK Pesat IT XPro
        </p>
    </div>

    <div class="card p-8">
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="label label-required">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="input" placeholder="John Doe">
                @error('name') <p class="error-text mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="label label-required">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="input" placeholder="nama@smkpesatpro.sch.id">
                @error('email') <p class="error-text mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="label label-required">Kata Sandi</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" class="input" placeholder="Minimal 8 Karakter">
                @error('password') <p class="error-text mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="label label-required">Konfirmasi Sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input" placeholder="Ulangi Kata Sandi">
            </div>

            <button type="submit" class="btn-primary w-full btn-lg mt-2 btn-shine">
                Daftar Sekarang
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-canvas-200 dark:border-brand-800 text-center">
            <p class="text-sm text-ink-500">
                Sudah Punya Akun?
                <a href="{{ route('login') }}" class="font-bold text-brand-700 dark:text-gold-400 hover:underline">
                    Masuk Di Sini
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
