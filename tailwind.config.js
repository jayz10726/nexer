/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // Blue family — primary CTAs, links, active states (anchored on #00BFFF / #00FFFF)
                brand: {
                    50: '#EBFEFF',
                    100: '#CCFBFF',
                    200: '#99F5FF',
                    300: '#4DEEFF',
                    400: '#00FFFF',
                    500: '#00BFFF',
                    600: '#0099D6',
                    700: '#0077AD',
                    800: '#003262',
                    900: '#00203F',
                    950: '#001529',
                },
                // Violet/purple family — gradients, secondary accents (#8A2BE2 / #6F2DA8)
                violet: {
                    50: '#F6EEFC',
                    100: '#E9D6F7',
                    200: '#D3ADEF',
                    300: '#B87FE6',
                    400: '#8A2BE2',
                    500: '#6F2DA8',
                    600: '#5C2589',
                    700: '#481D6B',
                    800: '#34154D',
                    900: '#210D31',
                },
                // Green family — success states, positive amounts (#03C03C / #00401A)
                forest: {
                    50: '#E9FBEE',
                    100: '#CCF5D6',
                    200: '#99EBAE',
                    300: '#5CDD7D',
                    400: '#03C03C',
                    500: '#029934',
                    600: '#02752A',
                    700: '#00401A',
                    800: '#00330F',
                },
                ink: {
                    900: '#00203F',
                    800: '#003262',
                },
            },
            fontFamily: {
                sans: ['"Inter"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
