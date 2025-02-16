module.exports = {
  postcssOptions: {
    plugins: [
      require('tailwindcss'),
      require('autoprefixer'),
      require('cssnano')({ preset: 'default' })
    ],
  },
};
