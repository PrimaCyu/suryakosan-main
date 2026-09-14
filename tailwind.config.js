/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          red: '#E60049',
          dark: '#3B2314',
          yellow: '#F3A833',
          teal: '#00A896',
          cream: '#fffaf7',
        }
      }
    },
  },
  plugins: [],
}