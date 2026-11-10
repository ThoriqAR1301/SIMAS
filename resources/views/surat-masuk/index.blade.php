<x-app-layout>
    <x-slot name="title">Arsip Surat Masuk</x-slot>
    <x-slot name="subtitle">Kelola, Cari, Dan Lihat Grafik Statistik Surat Masuk Dari Industri</x-slot>

    <div class="max-w-[1400px] mx-auto space-y-6 pb-12">
        
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-sm font-bold flex items-center gap-2 shadow-sm animate-in fade-in">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-[#F4B22E]/10 text-[#F4B22E] flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-gray-400">Total Surat Masuk</span>
                    <p class="text-3xl font-black text-gray-900 tracking-tighter mt-1">{{ $suratMasuks->total() }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-gray-400">Total Kategori Folder</span>
                    <p class="text-3xl font-black text-gray-900 tracking-tighter mt-1">{{ count($folders) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-gray-400">Aksi Cepat</span>
                    <p class="text-sm font-bold text-gray-900 mt-1">Input Surat Masuk</p>
                </div>
                <a href="{{ route('surat-masuk.create') }}" class="px-5 py-2.5 bg-[#F4B22E] hover:bg-[#d99a1a] text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    + Input
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)]">
                <div class="mb-6">
                    <h3 class="text-lg font-black text-gray-900">Surat Per Kategori Folder</h3>
                    <p class="text-xs font-semibold text-gray-500">Jumlah Surat Masuk Berdasarkan Folder</p>
                </div>
                <div class="h-64">
                    <canvas id="folderChart"
                        data-folder-labels="{{ json_encode($folders) }}"
                        data-folder-counts="{{ json_encode($folders->map(function ($f) {
                            return \App\Models\SuratMasuk::where('kategori_folder', $f)->count();
                        })) }}"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)]">
                <div class="mb-6">
                    <h3 class="text-lg font-black text-gray-900">Proporsi Kategori Surat</h3>
                    <p class="text-xs font-semibold text-gray-500">Persentase Pembagian Folder</p>
                </div>
                <div class="h-64 flex justify-center">
                    <canvas id="categoryDoughnutChart"
                        data-folder-labels="{{ json_encode($folders) }}"
                        data-folder-counts="{{ json_encode($folders->map(function ($f) {
                            return \App\Models\SuratMasuk::where('kategori_folder', $f)->count();
                        })) }}"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)]">
            <form method="GET" action="{{ route('surat-masuk.index') }}" class="flex flex-col gap-3">
                
                <div class="flex flex-col md:flex-row gap-3 w-full">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama surat / random code..." 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:border-[#F4B22E] focus:ring-4 focus:ring-[#F4B22E]/10 transition-all outline-none">
                    </div>

                    <div class="relative w-full md:w-72" id="folderDropdown">
                        <input type="hidden" name="folder" id="folderInput" value="{{ $folder ?? '' }}">

                        <button type="button" id="folderDropdownBtn"
                            class="w-full flex items-center justify-between px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-700 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:border-[#F4B22E]/60 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-[#F4B22E]/10 transition-all duration-300 group">
                            <span id="folderDropdownText" class="truncate pr-2">
                                {{ $folder ? $folder : 'Semua Kategori Folder' }}
                            </span>
                            <svg id="folderDropdownIcon" class="w-4 h-4 text-gray-400 group-hover:text-[#F4B22E] transition-all duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="folderDropdownMenu" class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] overflow-hidden hidden transform origin-top transition-all duration-200">
                            <ul class="max-h-60 overflow-y-auto p-2 space-y-1">
                                
                                <li class="folder-option flex items-center justify-between px-4 py-2.5 text-sm cursor-pointer rounded-xl transition-colors duration-150 {{ !$folder ? 'bg-[#F4B22E]/10 text-[#F4B22E] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                                    data-value="">
                                    <span>Semua Kategori Folder</span>
                                    <svg class="check-icon w-4 h-4 {{ !$folder ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </li>

                                @foreach($folders as $f)
                                    <li class="folder-option flex items-center justify-between px-4 py-2.5 text-sm cursor-pointer rounded-xl transition-colors duration-150 {{ ($folder == $f) ? 'bg-[#F4B22E]/10 text-[#F4B22E] font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
                                        data-value="{{ $f }}">
                                        <span class="truncate pr-2">{{ $f }}</span>
                                        <svg class="check-icon w-4 h-4 flex-shrink-0 {{ ($folder == $f) ? '' : 'hidden' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-[#0B1120] hover:bg-gray-900 text-white font-bold text-sm rounded-xl transition-colors">
                        Cari
                    </button>
                    
                    @if($search || $folder)
                        <a href="{{ route('surat-masuk.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition-colors text-center">
                            Reset
                        </a>
                    @endif
                </div>

                <a href="{{ route('surat-masuk.create') }}" class="w-full py-3.5 bg-[#F4B22E] hover:bg-[#d99a1a] text-white font-black text-sm uppercase tracking-widest rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2 mt-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Input Surat Masuk
                </a>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.03)] overflow-hidden">
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-8 py-5">Random Code</th>
                            <th class="px-8 py-5">Nama Surat</th>
                            <th class="px-8 py-5">Kategori Folder</th>
                            <th class="px-8 py-5">Asal Industri</th>
                            <th class="px-8 py-5">Tanggal Diterima</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($suratMasuks as $surat)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-[#F4B22E]">
                                        {{ $surat->random_code }}
                                    </span>
                                </td>

                                <td class="px-8 py-5">
                                    <div class="font-bold text-sm text-gray-900">
                                        {{ $surat->nama_surat }}
                                    </div>
                                </td>

                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-[#F4B22E]/10 text-[#d99a1a] border border-[#F4B22E]/20">
                                        {{ $surat->kategori_folder }}
                                    </span>
                                </td>

                                <td class="px-8 py-5">
                                    <div class="text-sm font-semibold text-gray-600">
                                        {{ $surat->asal_surat }}
                                    </div>
                                </td>

                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-600">
                                        {{ \Carbon\Carbon::parse($surat->tanggal_diterima)->translatedFormat('Y-m-d') }}
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <div class="flex flex-col items-end gap-1 text-xs font-bold">
                                        <div class="flex gap-2">
                                            <a href="{{ route('surat-masuk.show', $surat->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Detail</a>
                                            <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="text-gray-600 hover:text-gray-900 hover:underline">Edit</a>
                                        </div>
                                        <form action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus surat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 hover:underline mt-0.5">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-gray-900">Data Tidak Ditemukan</h3>
                                        <p class="text-xs text-gray-500 mt-1">Belum ada data surat masuk atau hasil pencarian tidak cocok</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($suratMasuks->hasPages())
                <div class="px-8 py-5 border-t border-gray-100 bg-white">
                    {{ $suratMasuks->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const dropdownBtn = document.getElementById('folderDropdownBtn');
            const dropdownMenu = document.getElementById('folderDropdownMenu');
            const dropdownText = document.getElementById('folderDropdownText');
            const dropdownIcon = document.getElementById('folderDropdownIcon');
            const hiddenInput = document.getElementById('folderInput');
            const options = document.querySelectorAll('.folder-option');

            if (dropdownBtn) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdownMenu.classList.toggle('hidden');
                    dropdownIcon.classList.toggle('rotate-180');
                    dropdownIcon.classList.toggle('text-[#F4B22E]');
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('rotate-180');
                        dropdownIcon.classList.remove('text-[#F4B22E]');
                    }
                });

                options.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.preventDefault();
                        const value = this.getAttribute('data-value');
                        const text = this.querySelector('span').innerText;

                        hiddenInput.value = value;

                        dropdownText.innerText = value === '' ? 'Semua Kategori Folder' : text;

                        options.forEach(opt => {
                            opt.classList.remove('bg-[#F4B22E]/10', 'text-[#F4B22E]', 'font-bold');
                            opt.classList.add('text-gray-600', 'hover:bg-gray-50', 'hover:text-gray-900');
                            opt.querySelector('.check-icon').classList.add('hidden');
                        });

                        this.classList.remove('text-gray-600', 'hover:bg-gray-50', 'hover:text-gray-900');
                        this.classList.add('bg-[#F4B22E]/10', 'text-[#F4B22E]', 'font-bold');
                        this.querySelector('.check-icon').classList.remove('hidden');

                        dropdownMenu.classList.add('hidden');
                        dropdownIcon.classList.remove('rotate-180');
                        dropdownIcon.classList.remove('text-[#F4B22E]');
                    });
                });
            }

            const folderCanvas = document.getElementById('folderChart');
            const folderLabels = JSON.parse(folderCanvas.dataset.folderLabels);
            const folderCounts = JSON.parse(folderCanvas.dataset.folderCounts);

            const colors = ['#F4B22E', '#1E293B', '#10b981', '#3b82f6', '#ec4899', '#8b5cf6'];

            const ctxFolder = folderCanvas.getContext('2d');
            new Chart(ctxFolder, {
                type: 'bar',
                data: {
                    labels: folderLabels.length ? folderLabels : ['Belum Ada Folder'],
                    datasets: [{
                        label: 'Jumlah Surat',
                        data: folderCounts.length ? folderCounts : [0],
                        backgroundColor: '#F4B22E', 
                        borderRadius: 6,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false } 
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            ticks: { precision: 0, font: {family: 'Plus Jakarta Sans', weight: 'bold'} },
                            grid: { borderDash: [5, 5], color: '#F1F5F9' }
                        },
                        x: {
                            ticks: { font: {family: 'Plus Jakarta Sans', weight: 'bold'} },
                            grid: { display: false }
                        }
                    }
                }
            });

            const ctxDoughnut = document.getElementById('categoryDoughnutChart').getContext('2d');
            new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: {
                    labels: folderLabels.length ? folderLabels : ['Belum Ada Folder'],
                    datasets: [{
                        data: folderCounts.length ? folderCounts : [1],
                        backgroundColor: colors.slice(0, folderLabels.length || 1),
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: { 
                                usePointStyle: true, 
                                boxWidth: 8,
                                font: {family: 'Plus Jakarta Sans', weight: 'bold', size: 11}
                            } 
                        } 
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>