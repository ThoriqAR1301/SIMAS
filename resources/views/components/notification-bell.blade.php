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
    class="relative">

    <button @click="toggle()" :class="open ? 'bg-gold-100 dark:bg-gold-900/30 text-gold-700 dark:text-gold-400' : 'text-ink-500 hover:text-brand-700 dark:text-ink-400 dark:hover:text-gold-400 hover:bg-ink-100 dark:hover:bg-brand-900'" class="relative p-2.5 rounded-xl transition-all duration-200">

        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <template x-if="unreadCount > 0">
            <span class="absolute top-1 right-1 min-w-[20px] h-5 px-1.5 flex items-center justify-center bg-gradient-gold text-brand-900 text-[10px] font-black rounded-full ring-2 ring-white dark:ring-brand-950 shadow-glow-gold">
                <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                <span class="absolute inset-0 rounded-full bg-gold-400 animate-ping opacity-30"></span>
            </span>
        </template>
    </button>

    <div x-show="open" x-cloak @click.outside="open = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-3 w-[420px] max-w-[calc(100vw-2rem)] bg-white/90 dark:bg-brand-950/90 backdrop-blur-2xl rounded-3xl shadow-[0_20px_70px_-10px_rgba(30,42,90,0.3)] border border-white/20 dark:border-brand-800 overflow-hidden z-50">

        <div class="relative overflow-hidden bg-gradient-premium noise">
            <div class="absolute -top-8 -right-8 w-32 h-32 rounded-full bg-gold-400/20 blur-2xl"></div>
            <div class="relative p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="glow-dot"></span>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gold-400">
                                Notifikasi
                            </p>
                        </div>
                        <h3 class="text-lg font-bold text-white tracking-tight">
                            <span x-text="unreadCount > 0 ? unreadCount + ' Belum Dibaca' : 'Semua Sudah Dibaca'"></span>
                        </h3>
                    </div>
                    <button x-show="notifications.some(n => !n.read_at)" @click="markAllRead()" class="text-[10px] font-bold uppercase tracking-widest text-gold-400 hover:text-gold-300 transition px-3 py-1.5 rounded-lg hover:bg-white/5">
                        Tandai Semua
                    </button>
                </div>

                <div class="flex items-center gap-1 p-1 rounded-2xl bg-black/20 backdrop-blur">
                    <template x-for="tab in tabs" :key="tab.key">
                        <button @click="activeTab = tab.key" :class="activeTab === tab.key ? 'bg-white text-brand-900 shadow-sm' : 'text-ink-300 hover:text-white'" class="flex-1 px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200" x-text="tab.label"></button>
                    </template>
                </div>
            </div>
        </div>

        <div class="max-h-[420px] overflow-y-auto">

            <template x-if="filtered().length === 0">
                <div class="px-6 py-16 text-center">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-br from-gold-100 to-gold-50 dark:from-brand-800 dark:to-brand-900 flex items-center justify-center mb-4 shadow-card">
                        <svg class="w-10 h-10 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-brand-900 dark:text-ink-50 mb-1" x-text="activeTab === 'all' ? 'Belum Ada Notifikasi' : 'Tidak Ada Di Kategori Ini'"></h4>
                    <p class="text-xs text-ink-500 max-w-[240px] mx-auto">
                        Notifikasi Akan Muncul Di Sini Saat Ada Aktivitas Baru
                    </p>
                </div>
            </template>

            <template x-for="(notif, idx) in filtered()" :key="notif.id">
                <a :href="notif.url" @click="markRead(notif.id)" class="relative flex items-start gap-4 px-5 py-4 border-b border-ink-100 dark:border-brand-800/60 transition-all duration-200 group" :class="!notif.read_at ? 'bg-gradient-to-r from-gold-50/50 to-transparent dark:from-gold-900/10' : 'hover:bg-ink-50 dark:hover:bg-brand-900/40'" :style="`animation-delay: ${idx * 30}ms`" x-init="$el.style.animation = 'slideUp 0.4s ease-out both'">

                    <span x-show="!notif.read_at" class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-gold-400 to-gold-600 rounded-r"></span>

                    <div class="flex-shrink-0 w-11 h-11 rounded-2xl flex items-center justify-center shadow-sm ring-1 ring-black/5 dark:ring-white/5 group-hover:scale-105 transition-transform" :class="iconBg(notif)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-sm font-bold text-brand-900 dark:text-ink-50 truncate" x-text="notif.judul"></p>
                            <span x-show="!notif.read_at" class="text-[8px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded bg-gold-400 text-brand-900">
                                BARU
                            </span>
                        </div>
                        <p class="text-xs text-ink-500 dark:text-ink-400 line-clamp-2 leading-relaxed" x-text="notif.pesan"></p>
                        <p class="text-[10px] text-ink-400 mt-1.5 font-medium" x-text="notif.waktu"></p>
                    </div>

                    <svg class="w-4 h-4 text-ink-300 group-hover:text-gold-500 group-hover:translate-x-0.5 transition-all flex-shrink-0 mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </template>
        </div>

        <div class="px-5 py-3 border-t border-ink-100 dark:border-brand-800 bg-gradient-to-r from-ink-50 to-white dark:from-brand-900/60 dark:to-brand-900">
            <a :href="indexUrl" class="flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-800 dark:text-gold-400 dark:hover:text-gold-300 transition group">
                Lihat Semua Notifikasi
                <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>

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

                if (!res.ok) {
                    throw new Error(`Unread count request failed with status ${res.status}`);
                }

                const data = await res.json();
                this.unreadCount = data.count ?? 0;
            } catch (error) {
                console.error('Unable to fetch unread notification count.', error);
            }
        },

        async fetchNotifications() {
            try {
                const res = await fetch(config.indexUrl, { headers: { 'Accept': 'application/json' }});

                if (!res.ok) {
                    throw new Error(`Notifications request failed with status ${res.status}`);
                }

                const data = await res.json();
                this.notifications = data.data ?? [];
            } catch (error) {
                console.error('Unable to fetch notifications.', error);
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

                if (!res.ok) {
                    throw new Error(`Mark notification as read failed with status ${res.status}`);
                }

                const n = this.notifications.find(n => n.id === id);
                if (n && !n.read_at) {
                    n.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Unable to mark notification as read.', error);
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

                if (!res.ok) {
                    throw new Error(`Mark all notifications as read failed with status ${res.status}`);
                }

                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            } catch (error) {
                console.error('Unable to mark all notifications as read.', error);
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
            }[aksi] ?? 'Notifikasi Baru';
        },

        iconBg(notif) {
            const aksi = notif.aksi ?? 'submitted';
            return {
                'submitted': 'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400',
                'verified': 'bg-gold-100 text-gold-700 dark:bg-gold-900/40 dark:text-gold-400',
                'approved': 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400',
                'rejected': 'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400',
                'revisi': 'bg-orange-100 text-orange-600 dark:bg-orange-900/40 dark:text-orange-400',
            }[aksi] ?? 'bg-ink-100 text-ink-600';
        },

        showToast(title, message, aksi) {
            const colors = {
                'submitted': 'border-blue-500',
                'verified': 'border-gold-500',
                'approved': 'border-emerald-500',
                'rejected': 'border-red-500',
                'revisi': 'border-orange-500',
            };
            const borderColor = colors[aksi] ?? 'border-brand-500';

            const toast = document.createElement('div');
            toast.className = `fixed bottom-6 right-6 bg-white dark:bg-brand-900 border-l-4 ${borderColor} shadow-2xl rounded-2xl p-4 max-w-sm z-[9999] animate-slide-in ring-1 ring-black/5`;
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-gold flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-brand-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p data-toast-title class="font-bold text-sm text-brand-900 dark:text-ink-50"></p>
                        <p data-toast-message class="text-xs text-ink-500 mt-0.5 line-clamp-2"></p>
                    </div>
                </div>
            `;

            toast.querySelector('[data-toast-title]').textContent = title;
            toast.querySelector('[data-toast-message]').textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        },
    };
}
</script>
@endpush