module.exports = {
  postcssOptions: {
    plugins: [
      require('autoprefixer'),
      require('cssnano')({ preset: 'default' })
    ],
  },
};
