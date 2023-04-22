/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/js/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
