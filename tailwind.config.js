import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                economica : 'economica',
                OpenSans : 'OpenSans',
            },
            colors: {
                primary : '#7e4a7c',
                secondary : '#b878b5',
                tertiary : '#ffeefe',
                blackTrans : '#000000bf',
                whiteTrans : '#ffffffbf'
            },
            screens : {
                lt : '300px'
            }
        },
    },

    plugins: [forms],
};
