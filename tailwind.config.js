import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },

            colors: {
                brand: {
                    50:  '#F2F4FB',
                    100: '#E3E7F5',
                    200: '#C2C9E5',
                    300: '#97A2D1',
                    400: '#6B78B8',
                    500: '#4A579F',
                    600: '#3A4685',
                    700: '#2B3567',   
                    800: '#232C5C',
                    900: '#1E2A5A',   
                    950: '#151D3F',   
                },
                gold: {
                    50:  '#FEF9EC',
                    100: '#FDF0C8',
                    200: '#FBE08D',
                    300: '#F8CB52',
                    400: '#F5B942',
                    500: '#F4B22E',   
                    600: '#D99A1A',
                    700: '#B37A12',
                    800: '#8F5F12',
                    900: '#764E13',
                },
                ink: {
                    50:  '#F7F8FA',
                    100: '#EBEEF3',
                    200: '#D5DAE3',
                    300: '#AEB7C7',
                    400: '#808EA6',
                    500: '#5C6B85',
                    600: '#46536B',
                    700: '#364156',
                    800: '#1F2738',
                    900: '#0E1421',
                },
                canvas: {
                    50:  '#FEFCF8',
                    100: '#FAF7F0',
                    200: '#F3EDE0',
                    300: '#E8DFC9',
                    400: '#D4C9AE',
                    500: '#B8A886',
                },
            },

            boxShadow: {
                'soft': '0 2px 8px -2px rgba(30, 42, 90, 0.08), 0 4px 16px -4px rgba(30, 42, 90, 0.06)',
                'card': '0 4px 24px -8px rgba(30, 42, 90, 0.12), 0 2px 8px -4px rgba(30, 42, 90, 0.06)',
                'lift': '0 12px 40px -12px rgba(30, 42, 90, 0.20), 0 4px 16px -6px rgba(30, 42, 90, 0.10)',
                'glow-gold': '0 0 0 3px rgba(244, 178, 46, 0.20)',
                'glow-navy': '0 0 0 3px rgba(30, 42, 90, 0.12)',
                'inner-nav': 'inset 0 1px 0 0 rgba(255,255,255,0.06)',
            },

            backgroundImage: {
                'gradient-brand': 'linear-gradient(135deg, #2B3567 0%, #1E2A5A 50%, #151D3F 100%)',
                'gradient-gold': 'linear-gradient(135deg, #F4B22E 0%, #D99A1A 100%)',
                'gradient-premium': 'linear-gradient(135deg, #2B3567 0%, #151D3F 60%, #1E2A5A 100%)',
                'gradient-mesh': 'radial-gradient(at 20% 0%, rgba(244,178,46,0.12) 0%, transparent 50%), radial-gradient(at 100% 100%, rgba(30,42,90,0.15) 0%, transparent 50%)',
            },

            animation: {
                'fade-in': 'fadeIn 0.4s ease-out',
                'slide-up': 'slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1)',
                'slide-in': 'slideIn 0.3s ease-out',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
                'shimmer': 'shimmer 2.5s linear infinite',
            },
            keyframes: {
                fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                slideUp: { '0%': { opacity: 0, transform: 'translateY(12px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                slideIn: { '0%': { opacity: 0, transform: 'translateX(20px)' }, '100%': { opacity: 1, transform: 'translateX(0)' } },
                pulseSoft: { '0%,100%': { opacity: 1 }, '50%': { opacity: 0.6 } },
                shimmer: { '0%': { backgroundPosition: '-1000px 0' }, '100%': { backgroundPosition: '1000px 0' } },
            },
        },
    },

    plugins: [forms],
};
