<x-app-layout title="Edit Surat Izin">
    <div class="top">
        <div>
            <h1>Edit Surat Izin</h1>
            <p class="sub">Ubah isi surat atau tanda tangan selama masih berstatus Pending</p>
        </div>
        <a href="{{ route('surat-izin.show', $surat) }}" class="btn btn-ghost">← Kembali</a>
    </div>

    <form method="POST" action="{{ route('surat-izin.update', $surat) }}" class="card">
        @csrf
        @method('PUT')
        @include('surat-izin._form')
        <div style="margin-top:24px;text-align:right">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-app-layout>