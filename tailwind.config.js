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
    plugins: [daisyui, tailwindtypography],
};
