<x-app-layout title="Akun Guru & Pegawai">
    <div class="top">
        <div>
            <h1>Akun Guru & Pegawai</h1>
            <p class="sub">Hanya Admin TU yang bisa menambah, mengubah, dan menghapus akun</p>
        </div>
        <a href="{{ route('admin.akun.create') }}" class="btn btn-primary">+ Tambah Akun</a>
    </div>

    @if(session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;padding:12px 16px;border-radius:12px;margin-bottom:20px;font-size:14px">{{ session('error') }}</div>
    @endif

    <form method="GET" class="filters">
        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / email...">
        <select name="role">
            <option value="">Semua role</option>
            @foreach($roles as $r)
                <option value="{{ $r->name }}" @selected(request('role') === $r->name)>{{ $r->display_name ?? $r->name }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary">Filter</button>
        @if(request()->hasAny(['cari','role']))
            <a href="{{ route('admin.akun.index') }}" class="btn btn-ghost">Reset</a>
        @endif
    </form>

    <div class="card" style="padding:8px 18px">
        @if($users->count())
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th style="text-align:right">Aksi</th></tr></thead>
                <tbody>
                @foreach($users as $u)
                    <tr>
                        <td><b>{{ $u->name }}</b></td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge" style="background:#f3f4f6;color:#374151">{{ $u->role->display_name ?? $u->role->name ?? '-' }}</span></td>
                        <td style="text-align:right;white-space:nowrap">
                            <a href="{{ route('admin.akun.edit', $u) }}" class="btn btn-ghost" style="padding:6px 14px">Edit</a>
                            @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.akun.destroy', $u) }}" style="display:inline"
                                      onsubmit="return confirm('Hapus akun {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-ghost" style="padding:6px 14px;color:#dc2626;border-color:#fecaca">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:14px 0">{{ $users->links() }}</div>
        @else
            <div class="empty">Tidak ada akun.</div>
        @endif
    </div>
</x-app-layout>