<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIMAS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            background-color: #FAFAFA !important;
            color: #0F172A !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: -2;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.04) 1px, transparent 1px);
            background-size: 50px 50px;
            mask-image: radial-gradient(circle at center, black 40%, transparent 90%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 90%);
            pointer-events: none;
        }

        .glow-orange {
            position: fixed;
            top: 10%;
            left: -10%;
            width: 50vw;
            height: 50vw;
            background: radial-gradient(circle, rgba(255, 90, 31, 0.15) 0%, transparent 60%);
            filter: blur(80px);
            z-index: -1;
            pointer-events: none;
        }
        
        .glow-lime {
            position: fixed;
            bottom: -10%;
            right: -10%;
            width: 40vw;
            height: 40vw;
            background: radial-gradient(circle, rgba(204, 255, 0, 0.15) 0%, transparent 60%);
            filter: blur(80px);
            z-index: -1;
            pointer-events: none;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 
                0 30px 60px -15px rgba(0,0,0,0.1), 
                0 0 0 1px rgba(0,0,0,0.02),
                inset 0 1px 0 rgba(255,255,255,1);
            border-radius: 2.5rem;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            background-color: #F8FAFC !important;
            border: 1px solid #E2E8F0 !important;
            color: #0F172A !important;
            border-radius: 1rem !important;
            padding: 1rem 1.25rem !important;
            width: 100% !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02) !important;
        }

        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="password"]:focus {
            background-color: #FFFFFF !important;
            border-color: #FF5A1F !important;
            outline: none !important;
            box-shadow: 0 0 0 4px rgba(255, 90, 31, 0.15) !important;
        }

        label {
            color: #334155 !important;
            font-size: 0.7rem !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        .font-serif-italic {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 600;
        }

        .float-slow { animation: float 8s ease-in-out infinite; }
        .float-fast { animation: float 6s ease-in-out infinite reverse; }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(var(--rot, 0deg)); }
            50% { transform: translateY(-15px) rotate(var(--rot, 0deg)); }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 sm:p-8">

    <div class="bg-grid"></div>
    <div class="glow-orange"></div>
    <div class="glow-lime"></div>

    <div class="relative w-full max-w-6xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-20 z-10">

        <div class="hidden lg:flex w-1/2 flex-col justify-center relative fade-in-up" style="animation-delay: 0.1s;">
            
            <div class="inline-flex items-center gap-2 mb-8">
                <span class="bg-[#1E293B] text-white text-[10px] font-bold px-4 py-2 rounded-full tracking-widest shadow-lg">SIMAS APP</span>
                <span class="bg-[#CCFF00] text-black text-[10px] font-bold px-4 py-2 rounded-full tracking-widest shadow-lg rotate-3">SISTEM TERPADU</span>
            </div>

            <h1 class="text-6xl font-extrabold text-gray-900 leading-[1.1] tracking-tight mb-6">
                Tinggalkan<br>
                Cara Lama.<br>
                <span class="font-serif-italic text-[#FF5A1F] font-normal text-7xl">Beralih</span> ke Digital.
            </h1>

            <p class="text-lg text-gray-500 font-medium max-w-md leading-relaxed mb-10">
                Kelola pengajuan surat, pantau status persetujuan, dan terima notifikasi secara langsung tanpa hambatan birokrasi.
            </p>

            <div class="flex items-center gap-4 mt-10">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center p-2 shadow-lg border border-gray-100">
                    <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="text-sm font-extrabold text-gray-900 tracking-widest">SMK INFORMATIKA PESAT IT XPRO</div>
                    <div class="text-xs font-medium text-gray-400">&copy; {{ date('Y') }} All rights reserved.</div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-[450px] relative fade-in-up" style="animation-delay: 0.3s;">
            
            <div class="absolute -top-12 -right-12 w-48 bg-white rounded-2xl p-4 shadow-xl border border-gray-100 rotate-[6deg] float-slow z-0 hidden lg:block" style="--rot: 6deg">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-xs">✓</div>
                    <div class="text-[10px] font-bold text-gray-800">Verifikasi Sukses</div>
                </div>
                <div class="h-1.5 w-full bg-gray-100 rounded-full mb-1"></div>
                <div class="h-1.5 w-2/3 bg-gray-100 rounded-full"></div>
            </div>

            <div class="absolute -bottom-10 -left-10 w-56 bg-[#1E293B] rounded-2xl p-5 shadow-2xl rotate-[-6deg] float-fast z-0 hidden lg:block" style="--rot: -6deg">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 rounded-full bg-[#CCFF00] animate-pulse"></span>
                    <span class="text-[10px] font-bold text-white uppercase tracking-wider">Notifikasi</span>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed">Ada pengajuan surat baru dari Guru.</p>
            </div>

            <div class="glass-panel p-8 sm:p-10 relative z-10">
                
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm border border-gray-100">
                        <img src="{{ asset('images/IT_XPRO-removebg-preview-removebg-preview.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-widest">SIMAS</span>
                </div>

                {{ $slot }}
                
            </div>
        </div>

    </div>

</body>
</html>
