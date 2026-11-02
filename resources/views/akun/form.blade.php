<x-app-layout title="{{ $akun ? 'Edit Akun' : 'Tambah Akun' }}">
    <div class="top">
        <div>
            <h1>{{ $akun ? 'Edit Akun' : 'Tambah Akun' }}</h1>
            <p class="sub">Nama di sini akan dipakai otomatis di surat izin. Role menentukan jabatan.</p>
        </div>
        <a href="{{ route('admin.akun.index') }}" class="btn btn-ghost">← Kembali</a>
    </div>

    <form method="POST" class="card"
          action="{{ $akun ? route('admin.akun.update', $akun) : route('admin.akun.store') }}">
        @csrf
        @if($akun) @method('PUT') @endif

        <div class="grid">
            <div>
                <label>Nama lengkap</label>
                <input type="text" name="name" value="{{ old('name', $akun->name ?? '') }}" required>
                @error('name')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Email (Gmail)</label>
                <input type="email" name="email" value="{{ old('email', $akun->email ?? '') }}" placeholder="nama@gmail.com" required>
                @error('email')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Role / Jabatan</label>
                <select name="role_id" required>
                    <option value="">Pilih role</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->id }}" @selected(old('role_id', $roleId) == $r->id)>{{ $r->display_name ?? $r->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<div class="err">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>{{ $akun ? 'Password baru (kosongkan jika tidak diganti)' : 'Password awal' }}</label>
                <div style="display:flex;gap:8px">
                    <input type="text" name="password" id="pw" autocomplete="off" {{ $akun ? '' : 'required' }} minlength="8">
                    <button type="button" class="btn btn-ghost" style="white-space:nowrap"
                            onclick="document.getElementById('pw').value=Array.from(crypto.getRandomValues(new Uint8Array(10))).map(b=>'abcdefghjkmnpqrstuvwxyz23456789'[b%31]).join('')">Acak</button>
                </div>
                @error('password')<div class="err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="margin-top:24px;text-align:right">
            <button type="submit" class="btn btn-primary">{{ $akun ? 'Simpan Perubahan' : 'Buat Akun' }}</button>
        </div>
    </form>
</x-app-layout>