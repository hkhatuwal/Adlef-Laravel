/** @type {import('tailwindcss').Config} */
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./node_modules/flowbite/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                visuletProLight: ['visulet-light', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],

            },
            colors: {
                'accent': '#CBFF77',
                'dark': '#212121',
                'light': 'rgb(244, 244, 244) !important',

            },
        },
    },
    plugins: [
        require('flowbite/plugin')({
            datatables: true,
        }),
    ],
}
