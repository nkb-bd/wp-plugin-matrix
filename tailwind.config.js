/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    'wp-boilerplate.php',
    './app/**/*.php',
    './resources/**/*.{vue,js}',
    "!./dist/css/style.css"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
