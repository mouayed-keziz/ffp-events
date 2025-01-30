import defaultTheme from "tailwindcss/defaultTheme";
import daisyui from "daisyui";
import tailwindtypography from "@tailwindcss/typography";
import colors from 'tailwindcss/colors'

/** @type {import('tailwindcss').Config} */
export default {
    // darkMode: false,
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    daisyui: {
        themes: [
            {
                "ffp-theme-light": {
                    "primary": colors.orange[500],
                    "primary-focus": colors.orange[600],
                    "primary-content": colors.white,
                    "secondary": colors.amber[400],
                    "secondary-focus": colors.amber[500],
                    "secondary-content": colors.white,
                    "accent": colors.cyan[400],
                    "accent-focus": colors.cyan[500],
                    "accent-content": colors.white,
                    "neutral": colors.stone[800],
                    "base-100": colors.gray[100],
                    "base-200": colors.gray[200],
                    "base-300": colors.gray[300],
                    "base-content": colors.gray[900],
                    "info": colors.blue[600],
                    "success": colors.green[500],
                    "warning": colors.amber[300],
                    "error": colors.red[400],

                    "--rounded-box": "0.4rem",
                    "--rounded-btn": "0.7rem",
                    "--rounded-badge": "1.9rem",
                },
                "ffp-theme-dark": {
                    "primary": colors.amber[400],
                    "primary-focus": colors.amber[500],
                    "primary-content": colors.zinc[900],
                    "secondary": colors.zinc[400],
                    "secondary-focus": colors.zinc[500],
                    "secondary-content": colors.zinc[900],
                    "accent": colors.cyan[400],
                    "accent-focus": colors.cyan[500],
                    "accent-content": colors.zinc[900],
                    "neutral": colors.zinc[300],
                    "base-100": colors.zinc[900],
                    "base-200": colors.zinc[800],
                    "base-300": colors.zinc[700],
                    "base-content": colors.zinc[100],
                    "info": colors.blue[400],
                    "success": colors.green[400],
                    "warning": colors.amber[300],
                    "error": colors.red[400],

                    "--rounded-box": "0.5rem",
                    "--rounded-btn": "0.3rem",
                    "--rounded-badge": "1.9rem",
                },
            },
        ],
    },
    plugins: [daisyui, tailwindtypography],
};
