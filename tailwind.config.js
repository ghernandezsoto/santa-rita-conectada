import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors'; // <-- Importar colores

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // --- SECCIÓN DE COLORES PERSONALIZADA ---
            colors: {
                // Color principal de la marca para el sidebar y elementos clave
                primary: colors.emerald, 
                // Color para acciones, botones principales y foco de formularios
                accent: colors.amber,
                // Colores para notificaciones
                success: colors.emerald,
                danger: colors.red,
                // Color de fondo general de la aplicación
                base: colors.slate,
            },
        },
    },

    plugins: [forms],
};