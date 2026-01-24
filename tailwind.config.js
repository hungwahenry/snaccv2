import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Sora', ...defaultTheme.fontFamily.sans],
                bold: ['Boldonse', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#fee2e4',
                    100: '#fdc5c9',
                    200: '#fb8b93',
                    300: '#f9515d',
                    400: '#f71727',
                    500: '#e63946',
                    600: '#c52d38',
                    700: '#93222a',
                    800: '#62171c',
                    900: '#310b0e',
                },
                brand: '#e63946',
                dark: {
                    bg: '#0a0a0a',
                    surface: '#1a1a1a',
                    border: '#2a2a2a',
                },
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.5rem',
                '3xl': '2rem',
                'full': '9999px',
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '128': '32rem',
            },
            boxShadow: {
                'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                'glow': '0 0 20px rgba(230, 57, 70, 0.3)',
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
            },
        },
    },

    plugins: [
        forms({
            strategy: 'class',
        }),
    ],
};
