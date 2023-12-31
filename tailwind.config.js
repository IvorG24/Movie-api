const colors = require('tailwindcss/colors')
module.exports = {
    content: ['./*.html'],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {
            colors: {
                //add your own color
                //https://tailwindcss.com/docs/customizing-colors
            },
            container: {
                center: true,
            },
            backgroundImage: {
                'netflix-image': "url('/assets/netflix.png')",
              },
            fontSize:{
                'logo': '200px'
            },
            boxShadow: {
                'white': '0 4px 6px -1px rgba(255, 255, 255, 0.1), 0 2px 4px -1px rgba(255, 255, 255, 0.06)'
              }
        },
    },
    variants: {
        extend: {},
    },
    plugins: [],
}