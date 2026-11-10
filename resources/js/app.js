"use strict";

import '../css/app.css';

import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.addEventListener('error', (event) => {
    console.error('%c[Global Error]', 'color: #ef4444; font-weight: bold;', event.message);
});

window.Alpine = Alpine;
Alpine.start();

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    window.Pusher = Pusher;

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

        console.log(
            '%c[WebSocket] Reverb Connection Initialized Successfully', 
            'color: #10b981; font-weight: bold; background: #ecfdf5; padding: 2px 6px; border-radius: 4px;'
        );

        window.Echo.connector.pusher.connection.bind('error', (err) => {
            console.warn('%c[WebSocket] Koneksi Terputus:', 'color: #f59e0b; font-weight: bold;', err);
        });

    } catch (error) {
        console.warn(
            '%c[WebSocket] Gagal terhubung ke Reverb. Pastikan server Reverb berjalan.', 
            'color: #ef4444; font-weight: bold;', 
            error
        );
    }
} else {
    console.warn(
        '%c[WebSocket] VITE_REVERB_APP_KEY tidak ditemukan di .env. Fitur Notifikasi Realtime Dinonaktifkan.', 
        'color: #f59e0b; font-weight: bold;'
    );
}