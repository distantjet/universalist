/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.js",
    "./src/**/*.php",
    "./render.php",
    "../../themes/**/*.php", // If you want to use it in themes
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}