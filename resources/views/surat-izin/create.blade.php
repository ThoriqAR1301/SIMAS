<x-app-layout title="Buat Surat Izin">
    <div class="top">
        <div>
            <h1>Buat Surat Izin</h1>
            <p class="sub">Isi data, tanda tangan, lalu surat siap di-print</p>
        </div>
        <a href="{{ route('surat-izin.index') }}" class="btn btn-ghost">← Kembali</a>
    </div>

    <form method="POST" action="{{ route('surat-izin.store') }}" class="card">
        @csrf
        @include('surat-izin._form')
        <div style="margin-top:24px;text-align:right">
            <button type="submit" class="btn btn-primary">Simpan & Buat Surat</button>
        </div>
    </form>
</x-app-layout>