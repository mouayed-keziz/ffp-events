import defaultTheme from "tailwindcss/defaultTheme";
import daisyui from "daisyui";
import tailwindtypography from "@tailwindcss/typography";

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
                mytheme: {
                    "primary": "#f59e0b",
                    "secondary": "#fbbf24",
                    "accent": "#22d3ee",
                    "neutral": "#292524",
                    "base-100": "#f3f4f6",
                    "info": "#2563eb",
                    "success": "#22c55e",
                    "warning": "#fcd34d",
                    "error": "#f87171",
                },
            },
            "light",
            "dark",
            "cupcake",
            "bumblebee",
            "emerald",
            "corporate",
            "synthwave",
            "retro",
            "cyberpunk",
            "valentine",
            "halloween",
            "garden",
            "forest",
            "aqua",
            "lofi",
            "pastel",
            "fantasy",
            "wireframe",
            "black",
            "luxury",
            "dracula",
            "cmyk",
            "autumn",
            "business",
            "acid",
            "lemonade",
            "night",
            "coffee",
            "winter",
            "dim",
            "nord",
            "sunset",
        ],
    },
    plugins: [daisyui, tailwindtypography],
};
