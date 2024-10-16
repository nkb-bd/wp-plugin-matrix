import('tailwindcss').Config
module.exports = {
  content: [
    'plugin-entry.php',
    './includes/**/*.php',
     './src/**/**/*.{vue,js}',
    "!./assets/css/style.css"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
