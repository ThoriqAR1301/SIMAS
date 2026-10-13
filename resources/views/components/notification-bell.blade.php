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

    <button
        @click="toggle()"
        class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        <span
            x-show="unreadCount > 0"
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            x-cloak
            class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-[10px] font-bold rounded-full animate-pulse">
        </span>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-50">

        <div class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                Notifikasi
                <span x-show="unreadCount > 0" x-text="'(' + unreadCount + ')'" class="text-xs text-red-500"></span>
            </h3>
            <button
                x-show="notifications.length > 0"
                @click="markAllRead()"
                class="text-xs text-blue-600 hover:underline">
                Tandai Semua Dibaca
            </button>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-12 text-center text-sm text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Belum Ada Notifikasi
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <a :href="notif.url" @click="markRead(notif.id)" class="flex items-start gap-3 px-4 py-3 border-b dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer" :class="!notif.read_at ? 'bg-blue-50/50 dark:bg-blue-900/10' : ''">

                    <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center" :class="iconBg(notif)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate"
                           x-text="notif.judul"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2"
                           x-text="notif.pesan"></p>
                        <p class="text-[10px] text-gray-400 mt-1"
                           x-text="notif.waktu"></p>
                    </div>

                    <span
                        x-show="!notif.read_at"
                        class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2">
                    </span>
                </a>
            </template>
        </div>

        <div class="px-4 py-2 border-t dark:border-gray-700 text-center">
            <a :href="indexUrl" class="text-xs text-blue-600 hover:underline">
                Lihat Semua Notifikasi
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

        init() {
            this.fetchUnreadCount();

            if (window.Echo) {
                window.Echo.private(`role.${config.role}`)
                    .listen('.pengajuan.status.changed', (data) => {
                        this.onRealtimeNotif(data);
                    });
            }

            setInterval(() => this.fetchUnreadCount(), 30000);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifications();
        },

        async fetchUnreadCount() {
            try {
                const res = await fetch(config.unreadUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) {
                    throw new Error(`Request failed with status ${res.status}`);
                }

                const data = await res.json();
                this.unreadCount = data.count ?? 0;
            } catch (e) {
                console.warn('Gagal Fetch Unread Count :', e);
            }
        },

        async fetchNotifications() {
            try {
                const res = await fetch(config.indexUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) {
                    throw new Error(`Request failed with status ${res.status}`);
                }

                const data = await res.json();
                this.notifications = data.data ?? [];
            } catch (e) {
                console.warn('Gagal Fetch Notifications :', e);
            }
        },

        async markRead(id) {
            const notif = this.notifications.find(n => n.id === id);
            if (!notif) return;

            if (notif.ephemeral) {
                if (!notif.read_at) {
                    notif.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }

                return;
            }

            try {
                const res = await fetch(config.markReadUrl.replace('__notification_id__', encodeURIComponent(id)), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });
                if (!res.ok) {
                    throw new Error(`Request failed with status ${res.status}`);
                }

                if (notif && !notif.read_at) {
                    notif.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (e) {
                console.warn('Gagal Mark Read :', e);
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
                    throw new Error(`Request failed with status ${res.status}`);
                }

                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            } catch (e) {
                console.warn('Gagal Mark All Read :', e);
            }
        },

        onRealtimeNotif(data) {
            this.unreadCount++;

            if (this.open) {
                this.notifications.unshift({
                    id: 'realtime-' + Date.now(),
                    judul: this.judulFromAksi(data.aksi),
                    pesan: `${data.pengaju} — ${data.nama_kegiatan}`,
                    url: data.url,
                    waktu: data.waktu,
                    read_at: null,
                    aksi: data.aksi,
                    ephemeral: true,
                });
            }

            this.showToast(this.judulFromAksi(data.aksi), data.nama_kegiatan);

        },

        judulFromAksi(aksi) {
            return {
                'submitted': 'Pengajuan Baru',
                'verified': 'Pengajuan Diverifikasi',
                'approved': 'Pengajuan Disetujui',
                'rejected': 'Pengajuan Ditolak',
                'revisi': 'Diminta Revisi',
            }[aksi] ?? 'Notifikasi Baru';
        },

        iconBg(notif) {
            const aksi = notif.aksi ?? 'submitted';
            return {
                'submitted': 'bg-blue-100 text-blue-600',
                'verified': 'bg-yellow-100 text-yellow-600',
                'approved': 'bg-green-100 text-green-600',
                'rejected': 'bg-red-100 text-red-600',
                'revisi': 'bg-orange-100 text-orange-600',
            }[aksi] ?? 'bg-gray-100 text-gray-600';
        },

        showToast(title, message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-white dark:bg-gray-800 border-l-4 border-blue-500 shadow-xl rounded-lg p-4 max-w-sm z-[9999] animate-slide-in';
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                    </svg>
                    <div class="flex-1">
                        <p data-notification-title class="font-medium text-sm text-gray-900 dark:text-gray-100"></p>
                        <p data-notification-message class="text-xs text-gray-500 mt-0.5"></p>
                    </div>
                </div>
            `;
            toast.querySelector('[data-notification-title]').textContent = title;
            toast.querySelector('[data-notification-message]').textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        },
    };
}
</script>
@endpush