import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    default: "#e14eca",
                    50: '#FCEBF9',
                    100: '#F9DAF4',
                    200: '#F3B7E9',
                    300: '#ED94DF',
                    400: '#E771D4',
                    500: '#E14ECA',
                    600: '#D324B8',
                    700: '#A31C8E',
                    800: '#731464',
                    900: '#430B3A',
                    950: '#2B0726'
                },
                secondary: {
                    default: "#ba54f5",
                    50: '#FEFDFF',
                    100: '#F4E5FD',
                    200: '#E1B4FB',
                    300: '#CD84F8',
                    400: '#BA54F5',
                    500: '#A51FF2',
                    600: '#860CCD',
                    700: '#630998',
                    800: '#410663',
                    900: '#1E032E',
                    950: '#0D0113'
                }
            }
        },
    },

    plugins: [forms, typography],
};
