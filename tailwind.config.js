import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Hanken Grotesk"', ...defaultTheme.fontFamily.sans],
                serif: ['"Instrument Serif"', ...defaultTheme.fontFamily.serif],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                ink: {
                    950: '#07090f',
                    900: '#0b0e16',
                    800: '#121725',
                    700: '#1b2233',
                    600: '#283149',
                },
                paper: {
                    DEFAULT: '#efe9df',
                    dim: '#a9adb8',
                    faint: '#6b7183',
                },
                brass: {
                    DEFAULT: '#e3a43a',
                    light: '#f2c46b',
                    dark: '#b97f1f',
                },
                navy: '#2a4a85',
            },
            letterSpacing: {
                label: '0.18em',
            },
            keyframes: {
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-50%)' },
                },
                'scroll-hint': {
                    '0%, 100%': { transform: 'translateY(0)', opacity: '0.3' },
                    '50%': { transform: 'translateY(8px)', opacity: '1' },
                },
            },
            animation: {
                marquee: 'marquee 40s linear infinite',
                'scroll-hint': 'scroll-hint 2.2s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
