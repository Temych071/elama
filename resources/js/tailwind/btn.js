const plugin = require('tailwindcss/plugin');

function generateColors(e, colors, prefix, outline = false) {
    return Object.keys(colors)
        .reduce((acc, key) => {
            if (typeof colors[key] === 'string') {
                const k = key === 'DEFAULT' ? prefix : `${prefix}-${e(key)}`;
                return {
                    ...acc,
                    [k]: outline ? {
                        color: colors[key],
                        borderWidth: 1,
                        borderColor: colors[key],
                    } : {
                        backgroundColor: colors[key],
                        borderWidth: 1,
                        borderColor: colors[key],
                        color: 'white',
                    },
                    [k + ':hover']: outline ? {
                        backgroundColor: colors[key],
                        color: 'white',
                    } : {
                        backgroundColor: colors[key],
                        color: 'white',
                    },
                };
            }

            return {
                ...acc,
                ...generateColors(e, colors[key], `${prefix}-${e(key)}`, outline),
            };
        }, '');
}

module.exports = plugin.withOptions(
    ({ btnClassName = 'btn', btnOutlineClassName = 'btn-outline' } = {}) =>
        function({addUtilities, e, theme, variants}) {
            const vars = variants('colors');
            const th = theme('colors');

            if(btnClassName)
                addUtilities(
                    generateColors(e, th, `.${btnClassName}`, false),
                    vars
                );

            if(btnOutlineClassName)
                addUtilities(
                    generateColors(e, th, `.${btnOutlineClassName}`, true),
                    vars
                );
        }
);
