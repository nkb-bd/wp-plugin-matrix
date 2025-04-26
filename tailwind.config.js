/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    'wp-plugin-matrix-starter.php',
    './app/**/*.php',
    './resources/**/*.{vue,js}',
    "!./dist/css/style.css"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
