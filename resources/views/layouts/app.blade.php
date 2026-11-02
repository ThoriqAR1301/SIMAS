<!DOCTYPE html>
<html lang="id" style="color-scheme:light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $attributes->get('title', 'SIMAS') }} • SIMAS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--primary:#111827;--primary-d:#000;--soft:#f3f4f6;--bg:#fff;--card:#fff;--text:#111827;--muted:#6b7280;--border:#e5e7eb;--hover:#f9fafb}
        *{box-sizing:border-box}
        html,body{background:#fff!important;color:#111827}
        body{margin:0;font-family:'Inter',system-ui,sans-serif;line-height:1.5;-webkit-font-smoothing:antialiased}
        a{color:inherit}

        .shell{display:flex;min-height:100vh}
        .side{width:250px;flex-shrink:0;background:#fff;border-right:1px solid var(--border);padding:22px 16px;display:flex;flex-direction:column;position:sticky;top:0;height:100vh;overflow-y:auto}
        .brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:18px;letter-spacing:-.02em;text-decoration:none;padding:0 8px 22px}
        .brand i{width:34px;height:34px;border-radius:10px;background:var(--primary);display:grid;place-items:center;color:#fff;font-style:normal;font-size:16px}
        .brand small{display:block;font-weight:500;font-size:11px;color:var(--muted);letter-spacing:0}
        .menu-label{font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;padding:14px 12px 6px}
        .side nav a{display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;font-size:14px;font-weight:500;color:var(--muted);text-decoration:none;margin-bottom:2px;transition:.15s}
        .side nav a:hover{background:var(--hover);color:var(--text)}
        .side nav a.active{background:var(--soft);color:var(--text);font-weight:600}
        .side svg{width:18px;height:18px;flex-shrink:0}
        .bell-badge{margin-left:auto;background:#ef4444;color:#fff;border-radius:999px;font-size:11px;font-weight:700;padding:1px 7px;display:none}
        .me{margin-top:auto;border-top:1px solid var(--border);padding-top:14px;display:flex;align-items:center;gap:10px}
        .me .av{width:36px;height:36px;border-radius:50%;background:var(--soft);color:var(--text);display:grid;place-items:center;font-weight:700;font-size:14px;flex-shrink:0}
        .me .who{min-width:0}
        .me b{display:block;font-size:13px;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px}
        .me span{font-size:12px;color:var(--muted)}
        .me button{margin-left:auto;background:none;border:0;color:var(--muted);cursor:pointer;padding:6px;border-radius:8px}
        .me button:hover{background:var(--soft);color:#ef4444}
        .main{flex:1;min-width:0;background:#fff}
        .si{max-width:1080px;margin:0 auto;padding:36px 32px 60px}

        .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:26px;flex-wrap:wrap;gap:12px}
        .si h1{font-size:26px;font-weight:700;letter-spacing:-.025em;margin:0}
        .sub{color:var(--muted);font-size:14px;margin:3px 0 0}
        .card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:26px}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;font-weight:600;font-size:14px;border:1px solid transparent;cursor:pointer;text-decoration:none;transition:.15s;font-family:inherit}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:var(--primary-d)}
        .btn-ghost{background:#fff;border-color:var(--border);color:var(--text)}
        .btn-ghost:hover{background:var(--hover)}
        .btn-green{background:#059669;color:#fff}.btn-green:hover{background:#047857}
        .btn-red{background:#dc2626;color:#fff}.btn-red:hover{background:#b91c1c}
        :where(.si) label{display:block;font-size:13px;font-weight:600;margin-bottom:6px}
        :where(.si) input:not([type=checkbox]):not([type=radio]),:where(.si) select,:where(.si) textarea{width:100%;padding:11px 14px;border:1px solid var(--border);border-radius:10px;font:inherit;font-size:14px;background:#fff;color:var(--text);transition:.15s}
        :where(.si) input:focus,:where(.si) select:focus,:where(.si) textarea:focus{outline:none;border-color:#111827;box-shadow:0 0 0 3px rgba(17,24,39,.08)}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}.full{grid-column:1/-1}
        .err{color:#dc2626;font-size:12px;margin-top:4px}
        .alert{background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:14px}
        :where(.si) table{width:100%;border-collapse:collapse;font-size:14px}
        :where(.si) th{text-align:left;color:var(--muted);font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.04em;padding:10px 12px;border-bottom:1px solid var(--border)}
        :where(.si) td{padding:14px 12px;border-bottom:1px solid var(--border)}
        :where(.si) tr:last-child td{border-bottom:none}
        .table-wrap{overflow-x:auto}
        .badge{padding:4px 11px;border-radius:999px;font-size:12px;font-weight:600;display:inline-block}
        .b-pending{background:#fef3c7;color:#b45309}
        .b-disetujui{background:#d1fae5;color:#047857}
        .b-ditolak{background:#fee2e2;color:#b91c1c}
        .empty{text-align:center;padding:48px 0;color:var(--muted)}
        .sig{border:2px dashed #d1d5db;border-radius:12px;background:#fff;touch-action:none;width:100%;height:180px;cursor:crosshair;display:block}
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:22px}
        .stat{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px 22px}
        .stat b{display:block;font-size:30px;letter-spacing:-.03em;line-height:1.2}
        .stat span{color:var(--muted);font-size:13px}
        .filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:18px}
        .filters input,.filters select{width:auto!important;flex:1;min-width:140px}
        .detail{display:grid;grid-template-columns:140px 1fr;gap:10px 16px;font-size:14px;margin:0}
        .detail dt{color:var(--muted)}.detail dd{margin:0}
        .actions{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:22px}
        .action{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px;text-decoration:none;transition:.15s;display:block}
        .action:hover{border-color:#111827;transform:translateY(-2px)}
        .action .ic{width:40px;height:40px;border-radius:12px;background:var(--soft);color:var(--text);display:grid;place-items:center;margin-bottom:14px}
        .action b{display:block;font-size:15px}.action span{font-size:13px;color:var(--muted)}
        .hello{border:1px solid var(--border);border-radius:14px;padding:26px 28px;margin-bottom:22px;background:#fff}
        .hello h1{font-size:24px}.hello p{margin:6px 0 0;color:var(--muted);font-size:14px}

        @media(max-width:860px){
            .shell{flex-direction:column}
            .side{width:100%;height:auto;position:static;border-right:0;border-bottom:1px solid var(--border);padding:14px}
            .brand{padding-bottom:10px}
            .side nav{display:flex;gap:6px;overflow-x:auto}.side nav a{white-space:nowrap;margin:0}
            .menu-label{display:none}.me{display:none}
            .si{padding:22px 16px 50px}
            .grid{grid-template-columns:1fr}.detail{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
@php
    $user      = auth()->user();
    $peran     = \App\Support\Peran::data($user);
    $roleLabel = $peran['label'];
    $isAdmin   = \App\Support\Peran::isAdmin($user);
    $isTU      = $peran['name'] === 'admin_tu';
@endphp
<div class="shell">
    <aside class="side">
        <a href="{{ route('dashboard') }}" class="brand">
            <i>S</i>
            <div>SIMAS<small>SMK Informatika Pesat</small></div>
        </a>

        <nav>
            <div class="menu-label">Menu</div>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M5 10v10h14V10"/></svg> Dashboard
            </a>
            <a href="{{ route('surat-izin.index') }}" class="{{ request()->routeIs('surat-izin.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8zM14 3v5h5M9 13h6M9 17h6"/></svg> {{ $isAdmin ? 'Surat Izin Saya' : 'Surat Izin' }}
            </a>
            <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/></svg> Pengajuan Surat
            </a>
            <a href="{{ route('izin-notif.index') }}" class="{{ request()->routeIs('izin-notif.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></svg> Notifikasi
                <span class="bell-badge" id="bellBadge">0</span>
            </a>

            @if($isAdmin)
                <div class="menu-label">Admin</div>
                <a href="{{ route('admin.surat-izin.index') }}" class="{{ request()->routeIs('admin.surat-izin.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg> Kelola Surat Izin
                </a>
                <a href="{{ route('approval.index') }}" class="{{ request()->routeIs('approval.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg> Approval
                </a>
                @if($isTU)
                    <a href="{{ route('admin.akun.index') }}" class="{{ request()->routeIs('admin.akun.*') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.9M16 3.1a4 4 0 010 7.8"/></svg> Akun Guru
                    </a>
                @endif
            @endif

            <div class="menu-label">Akun</div>
            <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg> Profil
            </a>
        </nav>

        <div class="me">
            <div class="av">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
            <div class="who">
                <b>{{ $user->name }}</b>
                <span>{{ $roleLabel }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <main class="si">
            @isset($header)
                <div class="top">{{ $header }}</div>
            @endisset

            @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif
            @if(session('status') && !session('success')) <div class="alert">{{ session('status') }}</div> @endif

            {{ $slot }}
        </main>
    </div>
</div>

<script>
    fetch('{{ route('izin-notif.jumlah') }}', {headers: {'Accept': 'application/json'}})
        .then(r => r.json())
        .then(d => {
            const n = d.count || 0;
            if (n > 0) { const b = document.getElementById('bellBadge'); b.textContent = n > 99 ? '99+' : n; b.style.display = 'inline-block'; }
        }).catch(() => {});
</script>
</body>
</html>