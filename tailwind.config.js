/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php", // Quét tất cả file PHP trong theme
    "./theme/*.php", // Quét tất cả file PHP trong theme
    "./theme/**/*.php",
    // "./assets/js/**/*.js", // Nếu có file JS cần dùng Tailwind
    // "./assets/css/**/*.css" // Nếu có CSS cần Tailwind
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
