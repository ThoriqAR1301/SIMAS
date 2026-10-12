<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Buat Pengajuan Surat
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pengajuan.store') }}" method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-5">
                @csrf

                @include('pengajuan.partials.form', ['pengajuan' => null, 'blanko' => $blanko])

                <div class="flex justify-end gap-2 pt-4 border-t dark:border-gray-700">
                    <a href="{{ route('pengajuan.index') }}"
                       class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Batal
                    </a>
                    <button type="submit" name="aksi" value="draft" :disabled="loading"
                        class="px-4 py-2 text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100">
                        Simpan Draft
                    </button>
                    <button type="submit" name="aksi" value="submit" :disabled="loading"
                        class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-700 text-white disabled:opacity-50">
                        <span x-show="!loading">Simpan & Ajukan</span>
                        <span x-show="loading">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>