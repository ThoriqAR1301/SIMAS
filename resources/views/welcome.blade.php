<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAS — Sistem Informasi Manajemen Administrasi Surat Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            background-color: #FAFAFA !important;
            color: #0F172A !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .bg-grid {
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
        }

        .text-hero {
            font-size: clamp(3.5rem, 7vw, 6.5rem);
            line-height: 1;
            letter-spacing: -0.04em;
            font-weight: 800;
        }
        .font-serif-italic {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 600;
        }

        .premium-shadow {
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1), 0 1px 3px rgba(0,0,0,0.05), inset 0 1px 0 rgba(255,255,255,0.8);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
        }

        .float-slow { animation: float 8s ease-in-out infinite; }
        .float-fast { animation: float 6s ease-in-out infinite reverse; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
            50% { transform: translateY(-15px) rotate(var(--rot, 0deg)); }
        }

        .blob-orange {
            background: radial-gradient(circle, rgba(255, 107, 53, 0.15) 0%, transparent 70%);
            filter: blur(40px);
        }
        .blob-lime {
            background: radial-gradient(circle, rgba(204, 255, 0, 0.2) 0%, transparent 70%);
            filter: blur(40px);
        }
        
        .bg-lime { background-color: #CCFF00 !important; }
        .text-orange { color: #FF5A1F !important; }
        .bg-orange { background-color: #FF5A1F !important; }
    </style>
</head>
<body class="antialiased selection:bg-[#CCFF00] selection:text-black relative">

    <div class="bg-grid"></div>

    <div class="fixed top-6 inset-x-0 z-50 flex justify-center px-4 pointer-events-none">
        <nav class="glass-nav rounded-full px-6 h-16 flex items-center justify-between w-full max-w-4xl pointer-events-auto transition-all duration-300 hover:shadow-lg">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-8 h-8 object-contain rounded-md">
                <span class="text-lg font-extrabold tracking-tight text-gray-900">SIMAS</span>
            </div>
            
            <div class="hidden md:flex gap-8 text-sm font-bold text-gray-500">
                <a href="#fitur" class="hover:text-black transition-colors">Fitur</a>
                <a href="#statistik" class="hover:text-black transition-colors">Data</a>
                <a href="#sistem" class="hover:text-black transition-colors">Sistem</a>
            </div>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-gray-900 text-white rounded-full font-bold text-sm hover:scale-105 transition-transform">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-black transition hidden sm:block">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-gray-900 text-white rounded-full font-bold text-sm hover:scale-105 transition-transform">Daftar</a>
                    @endauth
                @endif
            </div>
        </nav>
    </div>

    <section class="relative pt-40 pb-20 px-6 max-w-7xl mx-auto flex flex-col items-center text-center">
        
        <div class="absolute top-20 left-10 w-96 h-96 blob-orange rounded-full z-0 pointer-events-none"></div>
        <div class="absolute top-40 right-10 w-96 h-96 blob-lime rounded-full z-0 pointer-events-none"></div>

        <div class="relative z-10 mb-8 flex justify-center items-center gap-3">
            <span class="bg-[#00D084] text-white text-[10px] font-bold px-3 py-1.5 rounded-full rotate-[-6deg] shadow-lg">SISTEM TERPADU</span>
            <span class="bg-[#1E293B] text-white text-[10px] font-bold px-3 py-1.5 rounded-full rotate-[4deg] shadow-lg">MULTI-ROLE</span>
        </div>

        <h1 class="text-hero text-gray-900 relative z-10 max-w-4xl mx-auto mb-8">
            Kelola Surat, <br>
            <span class="font-serif-italic text-orange font-normal">Temukan</span> Efisiensi.
        </h1>

        <p class="text-lg md:text-xl text-gray-500 max-w-2xl mx-auto mb-12 font-medium relative z-10 leading-relaxed">
            Platform persuratan akademik, verifikasi, dan pengarsipan digital untuk SMK Informatika Pesat IT XPro. Cepat, transparan, dan terstruktur.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10 mb-20">
            <a href="{{ route('login') }}" class="px-8 py-4 bg-orange text-white font-bold rounded-full shadow-[0_15px_30px_rgba(255,90,31,0.3)] hover:scale-105 hover:shadow-[0_20px_40px_rgba(255,90,31,0.4)] transition-all flex items-center justify-center gap-2 group">
                Mulai Sekarang
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a href="#fitur" class="px-8 py-4 bg-white text-gray-900 border border-gray-200 font-bold rounded-full hover:bg-gray-50 hover:shadow-lg transition-all flex items-center justify-center">
                Pelajari Fitur
            </a>
        </div>

        <div class="relative w-full max-w-5xl mx-auto z-20" style="perspective: 2000px;">
            
            <div class="absolute top-20 -left-12 w-64 bg-white rounded-3xl p-5 premium-shadow border border-gray-100 rotate-[-8deg] float-slow z-10 hidden lg:block" style="--rot: -8deg">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">AF</div>
                    <div>
                        <div class="text-sm font-bold text-gray-900">Ahmad Fauzi</div>
                        <div class="text-[10px] text-gray-500">Guru Matematika</div>
                    </div>
                </div>
                <div class="h-2 w-full bg-gray-100 rounded-full mb-2"></div>
                <div class="h-2 w-2/3 bg-gray-100 rounded-full"></div>
                <div class="mt-4 px-3 py-1.5 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg inline-block border border-green-100">✓ Disetujui</div>
            </div>

            <div class="absolute top-32 -right-8 w-72 bg-[#1E293B] rounded-3xl p-6 shadow-2xl rotate-[6deg] float-fast z-10 hidden lg:block" style="--rot: 6deg">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-[#CCFF00] animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Notifikasi Baru</span>
                </div>
                <p class="text-sm text-gray-300 leading-relaxed">Pengajuan Surat Tugas dari Dewi Lestari menunggu verifikasi Anda.</p>
                <button class="mt-4 w-full py-2 bg-white/10 text-white text-xs font-bold rounded-lg border border-white/10">Cek Sekarang</button>
            </div>

            <div class="relative bg-white rounded-[2rem] p-3 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.15)] border border-gray-100 z-30">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-2xl">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    <div class="mx-auto px-4 py-1 bg-white border border-gray-200 rounded-md text-[10px] font-bold text-gray-400 tracking-wider">SIMAS.SMKPESATPRO.SCH.ID</div>
                </div>
                
                <div class="bg-[#FAFAFA] rounded-b-2xl overflow-hidden flex h-[400px] md:h-[500px]">
                    <div class="w-48 bg-white border-r border-gray-100 p-4 hidden md:flex flex-col gap-2">
                        <div class="h-8 bg-[#FF5A1F] rounded-lg mb-4 opacity-90"></div>
                        <div class="h-8 bg-gray-100 rounded-lg"></div>
                        <div class="h-8 bg-gray-50 rounded-lg"></div>
                        <div class="h-8 bg-gray-50 rounded-lg"></div>
                    </div>
                    
                    <div class="flex-1 p-6 relative">
                        <div class="flex justify-between items-center mb-6">
                            <div class="h-8 w-48 bg-gray-200 rounded-lg"></div>
                            <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <div class="text-xs text-gray-400 font-bold mb-1">TOTAL SURAT</div>
                                <div class="text-2xl font-black text-gray-900">1,240</div>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <div class="text-xs text-gray-400 font-bold mb-1">DISETUJUI</div>
                                <div class="text-2xl font-black text-[#00D084]">98%</div>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                            <div class="h-10 border-b border-gray-100 flex items-center px-4 bg-gray-50"><div class="h-3 w-1/4 bg-gray-200 rounded"></div></div>
                            <div class="h-14 border-b border-gray-50 flex items-center px-4"><div class="h-3 w-1/3 bg-gray-100 rounded"></div></div>
                            <div class="h-14 border-b border-gray-50 flex items-center px-4"><div class="h-3 w-1/3 bg-gray-100 rounded"></div></div>
                            <div class="h-14 flex items-center px-4"><div class="h-3 w-1/3 bg-gray-100 rounded"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="statistik" class="py-24 px-6 max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <div class="w-full lg:w-1/2 relative">
                <div class="bg-lime p-10 rounded-3xl rotate-[-2deg] shadow-2xl relative z-10 border border-[#b3e600]">
                    <h2 class="text-5xl font-extrabold leading-tight text-gray-900">data<br>terkini?</h2>
                    <div class="w-16 h-1.5 bg-gray-900 mt-6 mb-6"></div>
                    <p class="font-medium text-gray-800 text-lg">Pantau setiap progres administrasi sekolah secara langsung dan transparan.</p>
                    
                    <div class="absolute -bottom-6 left-10 bg-gray-900 text-white px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-3 shadow-xl">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#00D084] animate-pulse"></div> Live Data
                    </div>
                </div>
                <div class="absolute -top-10 -left-10 text-gray-300">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                </div>
            </div>
            
            <div class="w-full lg:w-1/2 relative h-[450px]">
                <div class="absolute top-0 right-0 w-4/5 bg-white p-8 rounded-3xl premium-shadow border border-gray-100 z-30 float-card">
                    <div class="text-6xl font-black text-gray-900 tracking-tighter">201<span class="text-orange">+</span></div>
                    <div class="text-lg font-bold text-gray-900 mt-2">Total Pengajuan</div>
                    <div class="text-sm text-gray-500 mt-1">Surat yang telah diproses sistem.</div>
                </div>
                <div class="absolute top-36 left-0 w-3/4 bg-white p-8 rounded-3xl shadow-lg border border-gray-100 z-20 float-card">
                    <div class="text-5xl font-black text-gray-900 tracking-tighter">95<span class="text-[#00D084]">%</span></div>
                    <div class="text-lg font-bold text-gray-900 mt-2">Terverifikasi</div>
                    <div class="text-sm text-gray-500 mt-1">Pengajuan disetujui tepat waktu.</div>
                </div>
                <div class="absolute bottom-0 right-12 w-2/3 bg-white p-8 rounded-3xl shadow-md border border-gray-100 z-10 float-card">
                    <div class="text-4xl font-black text-gray-900 tracking-tighter">25<span class="text-orange">+</span></div>
                    <div class="text-md font-bold text-gray-900 mt-1">Pengguna Aktif</div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-32 px-6 max-w-7xl mx-auto">
        <div class="text-center mb-20">
            <div class="inline-block bg-lime px-6 py-2 rounded-xl text-xl font-extrabold rotate-[-2deg] mb-8 shadow-lg border border-[#b3e600]">
                fitur utama
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Lebih dari Sekadar Sistem.</h2>
            <p class="text-xl text-gray-500 font-medium max-w-2xl mx-auto">Ekosistem digital yang dirancang untuk mempercepat birokrasi dan mempermudah pekerjaan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 bg-white p-10 rounded-[2rem] shadow-[0_20px_40px_-10px_rgba(0,0,0,0.08)] border border-gray-100 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-300">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gray-50 rounded-bl-full -z-0"></div>
                <div class="relative z-10">
                    <div class="inline-block bg-gray-100 text-gray-600 text-xs font-bold px-4 py-1.5 rounded-full mb-6">Workflow</div>
                    <h3 class="text-3xl font-extrabold mb-3 text-gray-900">Rekam Jejak Digital</h3>
                    <p class="text-gray-500 text-lg mb-10 max-w-md">Pantau alur persetujuan dari Guru, Admin TU, hingga Pimpinan secara transparan dan terpusat dengan timeline yang jelas.</p>
                    
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm max-w-md">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                            <div class="text-sm font-bold text-gray-500">Status Persetujuan</div>
                            <div class="text-[#00D084] font-bold text-xs bg-[#00D084]/10 px-3 py-1.5 rounded-lg border border-[#00D084]/20">DISETUJUI</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-white shadow-sm flex items-center justify-center text-xs font-bold text-gray-500">KS</div>
                            <div class="text-sm font-bold text-gray-800">Kepala Sekolah</div>
                            <div class="ml-auto text-xs text-gray-400 font-medium">10:00 AM</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-orange p-10 rounded-[2rem] shadow-2xl text-white relative overflow-hidden flex flex-col justify-between group hover:-translate-y-2 transition-transform duration-300">
                <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                <div>
                    <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mb-8 backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="text-xs font-bold bg-white/20 px-3 py-1 rounded-full inline-block mb-4 tracking-wider">REALTIME</div>
                    <h3 class="text-3xl font-extrabold mb-3 leading-tight">Notifikasi Instan</h3>
                    <p class="text-white/90 text-sm leading-relaxed">Sistem lonceng langsung berbunyi saat ada pengajuan baru, perubahan, atau penolakan.</p>
                </div>
            </div>

            <div class="md:col-span-3 bg-[#1E293B] p-10 rounded-[2rem] shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-grid opacity-10"></div>
                <div class="relative z-10 max-w-xl">
                    <h3 class="text-3xl font-extrabold text-white mb-3">Laporan & Rekapitulasi</h3>
                    <p class="text-gray-400 text-lg">Tersedia fitur ekspor laporan PDF dan Excel untuk keperluan akreditasi, rekap mingguan, dan bulanan.</p>
                </div>
                <div class="flex gap-4 relative z-10">
                    <div class="px-6 py-4 bg-white/10 text-white font-bold rounded-xl border border-white/20 flex items-center gap-2 hover:bg-white/20 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> PDF
                    </div>
                    <div class="px-6 py-4 bg-[#00D084] text-white font-bold rounded-xl flex items-center gap-2 hover:bg-[#00b873] transition cursor-pointer shadow-lg shadow-[#00D084]/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Excel
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="sistem" class="py-24 px-6 max-w-7xl mx-auto">
        <div class="bg-lime rounded-[3rem] p-12 md:p-24 text-center relative overflow-hidden shadow-2xl border border-[#b3e600]">
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle, #000 2px, transparent 2px); background-size: 30px 30px;"></div>
            
            <div class="relative z-10">
                <div class="inline-block bg-gray-900 text-white text-xs font-bold px-5 py-2.5 rounded-full mb-8 tracking-widest shadow-lg">MARI BERGABUNG!</div>
                <h2 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 max-w-4xl mx-auto leading-[1.1]">
                    Siap Melihat Potensi <br>Administrasi Sekolah?
                </h2>
                <p class="text-gray-800 font-medium text-lg mb-10 max-w-xl mx-auto">
                    Kelola pengajuan dan pengarsipan surat SMK Informatika Pesat IT XPro dengan lebih cerdas, cepat, dan terstruktur.
                </p>
                <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-gray-900 text-white font-bold text-lg rounded-full hover:scale-105 hover:bg-black transition-all shadow-xl shadow-gray-900/20">
                    Masuk ke Sistem
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-gray-200 py-12 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                <span class="font-extrabold text-gray-900 tracking-widest">SIMAS</span>
            </div>
            <p class="text-sm font-medium text-gray-500">&copy; {{ date('Y') }} SMK Informatika Pesat IT XPro. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
