/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./publik/**/*.{html,js}"],
  theme: {
    extend: {
      colors: {
        'cream' : '#FFDBB5',
        'coklat' : '#6C4E31',
        'boneWhite' : '#F7EED3',
        'merah' : '#BE1717'
      },

      fontFamily: {
        'questrial' : ['Questrial'],
        'audiowide' : ['Audiowide']
      }
    },
  },
  plugins: [],
}

