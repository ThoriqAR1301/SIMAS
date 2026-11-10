@php
    $userRole = auth()->user()->role?->name ?? 'guru';
    $notificationBellConfig = [
        'role' => $userRole,
        'unreadUrl' => route('notifikasi.unread-count'),
        'indexUrl' => route('notifikasi.index'),
        'markReadUrl' => route('notifikasi.baca', ['id' => '__notification_id__']),
        'markAllReadUrl' => route('notifikasi.baca-semua'),
    ];
@endphp

<div
    x-data="notificationBell(@js($notificationBellConfig))"
    x-init="init()"
    class="relative inline-block text-left"
    @click.outside="open = false">

    <button type="button" @click.stop="toggle()" 
            :class="open ? 'bg-[#0B1120] text-[#F4B22E] shadow-[0_0_20px_rgba(11,17,32,0.3)] border-[#0B1120]' : 'bg-white/80 dark:bg-[#0B1120]/80 backdrop-blur-md hover:bg-white dark:hover:bg-[#1E2A5A] text-gray-500 hover:text-[#0B1120] dark:text-gray-400 dark:hover:text-[#F4B22E] border-gray-200 dark:border-white/10'" 
            class="relative w-11 h-11 flex items-center justify-center rounded-2xl transition-all duration-500 focus:outline-none border shadow-sm hover:scale-105 group">
        
        <svg class="w-5 h-5 pointer-events-none transition-transform duration-500 group-hover:-translate-y-0.5" :class="open ? 'animate-wiggle' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 min-w-[20px] h-5 px-1.5 flex items-center justify-center bg-gradient-to-br from-[#FF5A1F] to-[#E04811] text-white text-[10px] font-black rounded-full ring-2 ring-white dark:ring-[#0B1120] shadow-[0_4px_12px_rgba(255,90,31,0.5)] pointer-events-none">
                <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                <span class="absolute inset-0 rounded-full bg-[#FF5A1F] animate-ping opacity-30"></span>
            </span>
        </template>
    </button>

    <div x-show="open" x-cloak 
         x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-500" 
         x-transition:enter-start="opacity-0 scale-95 translate-y-6" 
         x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
         x-transition:leave-end="opacity-0 scale-95 translate-y-6" 
         class="absolute right-0 mt-4 w-[440px] max-w-[calc(100vw-2rem)] 
                bg-white/90 dark:bg-[#0B1120]/90 backdrop-blur-3xl 
                rounded-[2rem] shadow-[0_30px_80px_-20px_rgba(0,0,0,0.25)] 
                border border-white/50 dark:border-white/10 
                overflow-hidden z-[999] flex flex-col origin-top-right 
                ring-1 ring-black/5 dark:ring-white/5">

        <div class="relative overflow-hidden bg-[#0B1120] p-6 rounded-t-[2rem]">
            <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#F4B22E] rounded-full blur-[60px] opacity-20 pointer-events-none"></div>
            
            <div class="relative z-10 flex items-start justify-between mb-5">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#CCFF00] animate-pulse shadow-[0_0_8px_#CCFF00]"></span>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-[#CCFF00]">Pusat Notifikasi</p>
                    </div>
                    <h3 class="text-xl font-black text-white tracking-tight">
                        <span x-text="unreadCount > 0 ? unreadCount + ' Pesan Baru' : 'Semua Dibaca'"></span>
                    </h3>
                </div>
                
                <button type="button" x-show="notifications.some(n => !n.read_at)" @click="markAllRead()" 
                        class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-[#0B1120] bg-white hover:bg-[#F4B22E] px-3 py-2 rounded-xl transition-all duration-300 shadow-sm">
                    Tandai Semua
                </button>
            </div>

            <div class="relative z-10 flex items-center p-1.5 rounded-2xl bg-black/40 border border-white/5 backdrop-blur-md">
                <template x-for="tab in tabs" :key="tab.key">
                    <button type="button" @click="activeTab = tab.key" 
                            :class="activeTab === tab.key ? 'bg-[#F4B22E] text-[#0B1120] shadow-lg scale-100' : 'text-gray-400 hover:text-white scale-95'" 
                            class="flex-1 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-300" 
                            x-text="tab.label"></button>
                </template>
            </div>
        </div>

        <div class="max-h-[380px] overflow-y-auto custom-scrollbar bg-white/50 dark:bg-transparent">

            <template x-if="filtered().length === 0">
                <div class="px-6 py-16 text-center flex flex-col items-center justify-center relative">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(244,178,46,0.05)_0%,transparent_70%)] pointer-events-none"></div>
                    
                    <div class="relative w-24 h-24 mb-6 flex items-center justify-center">
                        <div class="absolute inset-0 bg-[#F4B22E]/20 rounded-full blur-xl animate-pulse"></div>
                        <div class="relative w-16 h-16 rounded-3xl bg-white dark:bg-[#1E2A5A] border border-gray-100 dark:border-white/10 shadow-xl flex items-center justify-center rotate-3 transition-transform hover:rotate-0">
                            <svg class="w-8 h-8 text-[#F4B22E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="font-black text-gray-900 dark:text-white text-lg mb-1" x-text="activeTab === 'all' ? 'Kotak Masuk Bersih' : 'Tidak Ada Pesan'"></h4>
                    <p class="text-xs font-medium text-gray-500 max-w-[220px] leading-relaxed">
                        Kami akan memberi tahu Anda segera setelah ada pembaruan sistem
                    </p>
                </div>
            </template>

            <template x-for="(notif, idx) in filtered()" :key="notif.id">
                <a :href="notif.url" @click="markRead(notif.id)" 
                   class="group relative flex items-start gap-4 p-5 border-b border-gray-100/50 dark:border-white/5 transition-all duration-300 last:border-0"
                   :class="!notif.read_at 
                       ? 'bg-gradient-to-r from-orange-50/80 to-transparent dark:from-[#F4B22E]/10 dark:to-transparent hover:from-orange-100/80 dark:hover:from-[#F4B22E]/20' 
                       : 'bg-transparent hover:bg-gray-50 dark:hover:bg-white/5'">

                    <span x-show="!notif.read_at" class="absolute left-0 top-0 bottom-0 w-1.5 bg-[#FF5A1F] shadow-[0_0_15px_#FF5A1F]"></span>

                    <div class="flex-shrink-0 w-12 h-12 rounded-[1rem] flex items-center justify-center shadow-inner ring-1 ring-black/5 dark:ring-white/10 group-hover:scale-110 transition-transform duration-500" :class="iconBg(notif)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0 pt-1">
                        <div class="flex items-center justify-between gap-3 mb-1">
                            <p class="text-sm font-black truncate transition-colors"
                               :class="!notif.read_at ? 'text-[#0B1120] dark:text-white' : 'text-gray-600 dark:text-gray-300'"
                               x-text="notif.judul"></p>
                            
                            <span x-show="!notif.read_at" class="flex-shrink-0 px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-[#F4B22E] text-[#0B1120] shadow-sm">
                                Baru
                            </span>
                        </div>
                        
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed" x-text="notif.pesan"></p>
                        
                        <div class="flex items-center gap-1.5 mt-2.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="notif.waktu"></span>
                        </div>
                    </div>

                    <div class="w-8 h-8 rounded-full bg-white dark:bg-[#1E2A5A] shadow-sm flex items-center justify-center flex-shrink-0 mt-3 text-gray-400 group-hover:bg-[#0B1120] group-hover:text-[#F4B22E] transition-all duration-300 opacity-0 group-hover:opacity-100 -translate-x-3 group-hover:translate-x-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </template>
        </div>
        
    </div>
</div>

<style>
    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        50% { transform: rotate(-15deg); }
        75% { transform: rotate(10deg); }
    }
    .animate-wiggle {
        animation: wiggle 0.5s ease-in-out;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #E2E8F0;
        border-radius: 10px;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #374151;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: #CBD5E1;
    }
</style>

@push('scripts')
<script>
function notificationBell(config) {
    return {
        open: false,
        unreadCount: 0,
        notifications: [],
        activeTab: 'all',
        tabs: [
            { key: 'all', label: 'Semua' },
            { key: 'unread', label: 'Belum' },
        ],

        init() {
            this.fetchUnreadCount();

            if (window.Echo) {
                window.Echo.private(`role.${config.role}`)
                    .listen('.pengajuan.status.changed', (data) => this.onRealtimeNotif(data));
            }

            setInterval(() => this.fetchUnreadCount(), 30000);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifications();
        },

        filtered() {
            if (this.activeTab === 'unread') return this.notifications.filter(n => !n.read_at);
            return this.notifications;
        },

        async fetchUnreadCount() {
            try {
                const res = await fetch(config.unreadUrl, { headers: { 'Accept': 'application/json' }});
                if (!res.ok) throw new Error(`Unread count request failed with status ${res.status}`);
                const data = await res.json();
                this.unreadCount = data.count ?? 0;
            } catch (error) {
                console.error('Unable to fetch unread notification count', error);
            }
        },

        async fetchNotifications() {
            try {
                const res = await fetch(config.indexUrl, { headers: { 'Accept': 'application/json' }});
                if (!res.ok) throw new Error(`Notifications request failed with status ${res.status}`);
                const data = await res.json();
                this.notifications = data.data ?? [];
            } catch (error) {
                console.error('Unable to fetch notifications', error);
            }
        },

        async markRead(id) {
            
            try {
                const res = await fetch(config.markReadUrl.replace('__notification_id__', encodeURIComponent(id)), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                if (!res.ok) throw new Error(`Mark notification as read failed with status ${res.status}`);
                const n = this.notifications.find(n => n.id === id);
                if (n && !n.read_at) {
                    n.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Unable to mark notification as read', error);
            }
        },

        async markAllRead() {
            try {
                const res = await fetch(config.markAllReadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                if (!res.ok) throw new Error(`Mark all notifications as read failed with status ${res.status}`);
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            } catch (error) {
                console.error('Unable to mark all notifications as read', error);
            }
        },

        onRealtimeNotif(data) {
            this.unreadCount++;

            if (this.open) {
                this.notifications.unshift({
                    id: 'rt-' + Date.now(),
                    judul: this.judulFromAksi(data.aksi),
                    pesan: `${data.pengaju} — ${data.nama_kegiatan}`,
                    url: data.url,
                    waktu: data.waktu,
                    read_at: null,
                    aksi: data.aksi,
                    
                });
            }
            this.showToast(this.judulFromAksi(data.aksi), data.nama_kegiatan, data.aksi);
        },

        judulFromAksi(aksi) {
            return {
                'submitted': 'Pengajuan Baru Masuk',
                'verified': 'Pengajuan Diverifikasi',
                'approved': 'Pengajuan Disetujui',
                'rejected': 'Pengajuan Ditolak',
                'revisi': 'Permintaan Revisi',
                'updated': 'Pengajuan Diperbarui',
                'deleted': 'Pengajuan Dihapus',
            }[aksi] ?? 'Notifikasi Baru';
        },

        iconBg(notif) {
            const aksi = notif.aksi ?? 'submitted';
            return {
                'submitted': 'bg-blue-50 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400',
                'verified': 'bg-orange-50 text-[#FF5A1F] dark:bg-[#FF5A1F]/20 dark:text-[#FF5A1F]',
                'approved': 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400',
                'rejected': 'bg-red-50 text-red-600 dark:bg-red-500/20 dark:text-red-400',
                'revisi': 'bg-gold-50 text-gold-600 dark:bg-gold-500/20 dark:text-gold-400',
                'updated': 'bg-blue-50 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400',
                'deleted': 'bg-red-50 text-red-600 dark:bg-red-500/20 dark:text-red-400',
            }[aksi] ?? 'bg-gray-50 text-gray-600 dark:bg-white/10 dark:text-gray-400';
        },

        showToast(title, message, aksi) {
            const colors = {
                'submitted': 'border-blue-500',
                'verified': 'border-[#FF5A1F]',
                'approved': 'border-emerald-500',
                'rejected': 'border-red-500',
                'revisi': 'border-gold-500',
                'updated': 'border-blue-500',
                'deleted': 'border-red-500',
            };
            const borderColor = colors[aksi] ?? 'border-gray-500';

            const toast = document.createElement('div');
            toast.className = `fixed bottom-8 right-8 bg-white/95 dark:bg-[#0B1120]/95 backdrop-blur-2xl border-l-4 ${borderColor} shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] rounded-2xl p-5 max-w-sm z-[9999] animate-in slide-in-from-right duration-500 ring-1 ring-black/5 dark:ring-white/5`;
            toast.innerHTML = `
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#0B1120] dark:bg-white/10 flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-5 h-5 text-[#CCFF00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <p data-toast-title class="font-black text-sm text-[#0B1120] dark:text-white"></p>
                        <p data-toast-message class="text-xs font-medium text-gray-500 mt-1 line-clamp-2"></p>
                    </div>
                </div>
            `;

            toast.querySelector('[data-toast-title]').textContent = title;
            toast.querySelector('[data-toast-message]').textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                setTimeout(() => toast.remove(), 400);
            }, 5000);
        },
    };
}
</script>
@endpush