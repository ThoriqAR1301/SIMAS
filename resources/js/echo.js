import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

window.Pusher = Pusher;

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    try {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbKey,
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        window.Echo.connector.pusher.connection.bind('state_change', (states) => {
            const { current } = states;
            
            window.dispatchEvent(new CustomEvent('reverb-status', { detail: current }));

            switch (current) {
                case 'connecting':
                    console.log('%c[Reverb] Menghubungkan ke server...', 'color: #f59e0b; font-weight: bold;');
                    break;
                case 'connected':
                    console.log('%c[Reverb] Terhubung! Notifikasi realtime aktif.', 'color: #10b981; font-weight: bold; background: #ecfdf5; padding: 2px 6px; border-radius: 4px;');
                    break;
                case 'disconnected':
                    console.warn('%c[Reverb] Koneksi terputus. Mencoba menghubungkan ulang...', 'color: #ef4444; font-weight: bold;');
                    break;
                case 'failed':
                    console.error('%c[Reverb] Gagal terhubung. Pastikan server WebSocket (php artisan reverb:start) berjalan.', 'color: #ef4444; font-weight: bold;');
                    break;
            }
        });

    } catch (error) {
        console.error('%c[Reverb Init Error]', 'color: #ef4444; font-weight: bold;', error);
    }
} else {
    console.warn('%c[Reverb] VITE_REVERB_APP_KEY tidak ditemukan. Fitur realtime dinonaktifkan.', 'color: #f59e0b; font-weight: bold;');
}