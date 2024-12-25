import defaultTheme from "tailwindcss/defaultTheme";
import daisyui from "daisyui";
import tailwindtypography from "@tailwindcss/typography";
import colors from 'tailwindcss/colors'

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: false,
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
                "ffp-theme": {
                    "primary": colors.orange[500],
                    "secondary": colors.orange[400],
                    "accent": colors.cyan[400],
                    "neutral": colors.stone[800],
                    "base-100": colors.gray[100],
                    "info": colors.blue[600],
                    "success": colors.green[500],
                    "warning": colors.amber[300],
                    "error": colors.red[400],

                    // Custom border radius
                    "--rounded-box": "0.5rem",     // card, modal
                    "--rounded-btn": "0.3rem",   // button, input
                    "--rounded-badge": "1.9rem", // badge
                },
            },
        ],
    },
    plugins: [daisyui, tailwindtypography],
};
