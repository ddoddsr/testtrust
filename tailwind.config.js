/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors') 
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    './vendor/filament/**/*.blade.php', 
    // "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: { 
          danger: colors.rose,
          primary: colors.blue,
          success: colors.green,
          warning: colors.yellow,
      }, 
    },
  },
  // plugins: [],
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
}

