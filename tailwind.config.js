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
                'accent': 'rgba(95,195,246,0.36)',
                'dark': '#212121',
                'primary': '#2079b3',
                'light': 'rgb(241,247,253)',

            },
        },
    },
    plugins: [
        require('flowbite/plugin')({
            datatables: true,
        }),
    ],
}
