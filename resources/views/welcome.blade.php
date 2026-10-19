<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAS — Sistem Informasi Manajemen Administrasi Surat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(30, 42, 90, 0.04) 1px, transparent 1px), linear-gradient(to bottom, rgba(30, 42, 90, 0.04) 1px, transparent 1px);
        }
        .dark .bg-grid { background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
        .floating { animation: float 6s ease-in-out infinite; }
        .floating-delayed { animation: float 6s ease-in-out 3s infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
            50% { transform: translateY(-15px) rotate(var(--rot, 0deg)); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-canvas-100 text-ink-800 dark:bg-brand-950 dark:text-ink-100 overflow-x-hidden">

    <nav class="fixed top-0 inset-x-0 z-50 glass border-b border-canvas-200/50 dark:border-brand-800/50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <span class="text-xl font-extrabold tracking-tight text-brand-900 dark:text-white">SIMAS</span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-ink-600 dark:text-ink-300">
                <a href="#fitur" class="hover:text-gold-600 transition">Fitur</a>
                <a href="#statistik" class="hover:text-gold-600 transition">Statistik</a>
                <a href="#tentang" class="hover:text-gold-600 transition">Tentang</a>
            </div>
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary btn-sm">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-brand-900 dark:text-white hover:text-gold-600 transition">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-outline btn-sm">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="relative pt-40 pb-20 lg:pt-48 lg:pb-32 bg-grid overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-gold-400/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-900 text-white text-xs font-bold uppercase tracking-widest mb-8 shadow-lift ring-1 ring-white/20">
                <span class="w-2 h-2 rounded-full bg-gold-400 animate-pulse"></span>
                Platform Administrasi Terpadu
            </div>

            <h1 class="text-5xl lg:text-7xl font-black tracking-tighter text-brand-950 dark:text-white leading-[1.1] mb-6 text-balance">
                Kelola Surat <span class="relative inline-block text-transparent bg-clip-text bg-gradient-to-r from-gold-500 to-gold-600">Tanpa Ribet</span><br>
                Cepat & Transparan
            </h1>

            <p class="text-lg lg:text-xl text-ink-500 dark:text-ink-300 max-w-2xl mx-auto mb-10 text-balance">
                Tinggalkan Proses Manual. Ajukan Surat, Pantau Status Persetujuan, Dan Terima Notifikasi Secara <i>Real-Time</i> Dalam Satu Genggaman.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="btn-gold btn-lg w-full sm:w-auto btn-shine">
                    Mulai Gunakan SIMAS
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#fitur" class="btn-outline btn-lg w-full sm:w-auto bg-white/50 dark:bg-brand-900/50">
                    Pelajari Fitur
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 mt-20 relative h-[400px] lg:h-[500px] hidden md:block" x-data>
            <div class="absolute top-10 left-10 w-72 card p-4 floating" style="--rot: -6deg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-gold flex items-center justify-center font-bold text-brand-900">A</div>
                    <div>
                        <div class="text-sm font-bold">Ahmad Fauzi</div>
                        <div class="text-[10px] text-ink-500">Guru Matematika</div>
                    </div>
                </div>
                <div class="h-2 w-full bg-canvas-200 rounded-full mb-2"></div>
                <div class="h-2 w-3/4 bg-canvas-200 rounded-full mb-4"></div>
                <div class="px-3 py-2 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg inline-block border border-emerald-200">✓ Disetujui</div>
            </div>

            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[320px] card p-0 overflow-hidden shadow-2xl z-20 floating-delayed ring-4 ring-gold-400/20" style="--rot: 0deg">
                <div class="bg-gradient-premium p-6 text-white">
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gold-400 mb-1">Pengajuan Baru</div>
                    <div class="text-lg font-bold leading-tight">Rapat Koordinasi MGMP</div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b border-canvas-200 pb-4">
                        <div class="text-xs text-ink-500">Nomor</div>
                        <div class="font-mono text-xs font-bold text-brand-900">PGJ/2025/10/0001</div>
                    </div>
                    <div class="flex justify-between items-center mb-4 border-b border-canvas-200 pb-4">
                        <div class="text-xs text-ink-500">Tanggal</div>
                        <div class="text-xs font-bold">15 Okt 2025</div>
                    </div>
                    <div class="w-full btn-gold btn-sm">Verifikasi Sekarang</div>
                </div>
            </div>

            <div class="absolute top-20 right-10 w-64 card p-5 floating" style="--rot: 6deg">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-gold-500 animate-ping"></span>
                    <span class="text-xs font-bold text-brand-900">Notifikasi Realtime</span>
                </div>
                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-lg bg-gold-100 flex items-center justify-center flex-shrink-0 text-gold-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold">Pengajuan Disetujui</div>
                        <div class="text-[10px] text-ink-500 mt-1">Oleh Pimpinan • 2 Mnt Lalu</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="statistik" class="py-24 bg-white dark:bg-brand-900 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-5xl font-black text-brand-900 dark:text-white mb-4 tracking-tighter">
                    Data <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-500 to-gold-600">Terpusat</span>
                </h2>
                <p class="text-ink-500 text-lg">Pantau Seluruh Aktivitas Administrasi Secara Akurat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card p-8 bg-gradient-premium text-white shadow-lift relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gold-400/20 blur-2xl group-hover:scale-150 transition-transform"></div>
                    <div class="text-6xl font-black mb-2">100%</div>
                    <div class="text-sm font-bold uppercase tracking-widest text-gold-400">Paperless</div>
                    <p class="text-xs text-ink-300 mt-4 leading-relaxed">Tanpa Kertas, Tanpa Tumpukan Berkas. Semua Arsip Tersimpan Digital Dan Aman.</p>
                </div>
                <div class="card p-8 bg-white dark:bg-brand-800 shadow-card text-center flex flex-col justify-center">
                    <div class="text-5xl font-black text-brand-900 dark:text-white mb-2">2x</div>
                    <div class="text-sm font-bold text-ink-500">Lebih Cepat</div>
                </div>
                <div class="card p-8 bg-gradient-gold text-brand-900 shadow-lift relative overflow-hidden">
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/20 rounded-full"></div>
                    <div class="text-5xl font-black mb-2">24/7</div>
                    <div class="text-sm font-bold uppercase tracking-widest text-brand-800">Akses</div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-canvas-100 dark:bg-brand-950 bg-grid">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-1 bg-brand-900 text-white text-xs font-bold rounded-full mb-4">FITUR UNGGULAN</div>
                <h2 class="text-3xl lg:text-5xl font-black text-brand-900 dark:text-white mb-4 tracking-tighter text-balance">
                    Bukan Sekadar Sistem,<br>Tapi Ekosistem Kerja.
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 card p-8 lg:p-10 hover:shadow-lift transition-shadow duration-300">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold mb-6">
                        Workflow
                    </div>
                    <h3 class="text-2xl font-bold text-brand-900 dark:text-white mb-3">Approval 2 Level Terstruktur</h3>
                    <p class="text-ink-500 mb-8 max-w-md">Alur Persetujuan Dari Guru &rarr; Admin TU &rarr; Pimpinan. Transparan Dengan Timeline Yang Jelas.</p>
                    
                    <div class="bg-ink-50 dark:bg-brand-900/50 rounded-2xl p-6 border border-canvas-200 dark:border-brand-800">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">✓</div>
                            <div class="h-2 w-1/3 bg-emerald-200 rounded-full"></div>
                        </div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">✓</div>
                            <div class="h-2 w-1/2 bg-emerald-200 rounded-full"></div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-gold-100 text-gold-600 flex items-center justify-center animate-pulse">●</div>
                            <div class="h-2 w-1/4 bg-gold-200 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <div class="card p-8 bg-brand-900 text-white relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-b from-brand-900/0 to-brand-950/80"></div>
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-400/20 text-gold-300 text-xs font-bold mb-6">
                            Security
                        </div>
                        <h3 class="text-2xl font-bold mb-3">QR Code Verifikasi</h3>
                        <p class="text-ink-300 text-sm mb-8">Surat Yang Dicetak Memiliki QR Code Keaslian Yang Bisa Di-Scan Publik.</p>
                        
                        <div class="mx-auto w-40 h-40 bg-white rounded-2xl p-2 shadow-glow-gold rotate-3 group-hover:rotate-0 transition-transform duration-500 flex items-center justify-center">
                            <svg class="w-full h-full text-brand-900" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 13h6v6H3v-6zm2 2v2h2v-2H5zm13-2h3v2h-3v-2zm-3 4h2v2h-2v-2zm2-2h2v2h-2v-2zm-2-2h2v2h-2v-2zm2 4h2v2h-2v-2z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="card p-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-100 text-gold-700 text-xs font-bold mb-6">
                        Notifikasi
                    </div>
                    <h3 class="text-xl font-bold text-brand-900 dark:text-white mb-2">Realtime Alert</h3>
                    <p class="text-ink-500 text-sm">Lonceng Notifikasi Langsung Berbunyi Saat Ada Pengajuan Masuk.</p>
                </div>

                <div class="md:col-span-2 card p-8 bg-gradient-to-br from-canvas-50 to-canvas-200 dark:from-brand-900 dark:to-brand-800 flex items-center gap-8">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-brand-900 dark:text-white mb-2">Laporan & Rekap</h3>
                        <p class="text-ink-500 text-sm">Export Ke PDF Atau Excel Untuk Laporan Bulanan dan Mingguan Secara Otomatis.</p>
                    </div>
                    <div class="w-24 h-24 bg-white rounded-2xl shadow-card flex items-center justify-center text-3xl flex-shrink-0">
                        📊
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-brand-950 text-white py-16 border-t border-brand-800 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/10 blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 pb-12 border-b border-brand-800">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-10 h-10 brightness-0 invert">
                    <span class="text-2xl font-black tracking-tight">SIMAS</span>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="btn-gold">Masuk Sistem</a>
                </div>
            </div>
            <div class="pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-ink-400">
                <p>&copy; {{ date('Y') }} SMK Pesat Pro. All Rights Reserved.</p>
                <p class="mt-2 md:mt-0">Dirancang Untuk Administrasi Masa Depan.</p>
            </div>
        </div>
    </footer>
</body>
</html>
