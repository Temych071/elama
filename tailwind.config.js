const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Mulish', ...defaultTheme.fontFamily.sans],
                logo: ['Mulish', ...defaultTheme.fontFamily.sans],
                button: ['Mulish', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                logo: '1.88rem'
            },
            dropShadow: {
                card: '0px 4px 6px #E5E5E5',
            },
            colors: {
                background: '#FFFFFF',
                sidebar: '#F6F8FA',
                card: {
                    surface: '#FFFFFF',
                    border: '#DFE0EB',
                },
                primary: {
                    lighter: '#E8F1FF',
                    light: '#3987FF',
                    DEFAULT: '#339AF0',
                    dark: '#0061F4',
                },
                success: {
                    lighter: '#ECFBE6',
                    light: '#51FD00',
                    DEFAULT: '#44D600',
                    dark: '#3BB900',
                },
                warning: {
                    lighter: '#FFF6E8',
                    light: '#FFAF39',
                    DEFAULT: '#FFA31A',
                    dark: '#F49200',
                },
                error: {
                    lighter: '#FFE8EC',
                    light: '#FF395C',
                    DEFAULT: '#FF1A43',
                    dark: '#F4002C',
                },
                info: {
                    lighter: '#EBF9FF',
                    light: '#4ECAFF',
                    DEFAULT: '#33C2FF',
                    dark: '#0AB6FF',
                },
                white: {
                    DEFAULT: '#FFFFFF',
                    '30': 'rgba(255, 255, 255, .2)',
                    '50': 'rgba(255, 255, 255, .5)',
                },
                gray: {
                    dark: '#4B506D',
                    'exlight': '#FCFDFE',
                    lightest: '#F0F1F7',
                    tableBorder: '#DFE0EB',
                    DEFAULT: '#9FA2B4',
                },
                black: {
                    DEFAULT: '#223354',
                    '30': 'rgba(34, 51, 84, .2)',
                    '50': 'rgba(34, 51, 84, .5)',
                },
                secondary: {
                    DEFAULT: '#DCE4EA',
                },
            },
            width: {
                modal: '23.75rem',
                sidebar: '17.5rem',
                sidebar_collapsed: '80px',
            },
            height: {
                desktop_header: '4.7rem'
            },
        },
    },

    variants: {
        extend: {
            opacity: ['active'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('./resources/js/tailwind/btn')(),
    ],
};
