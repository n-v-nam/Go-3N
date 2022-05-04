module.exports = {
  devServer: {
    disableHostCheck: true,
    host: 'localhost'
  },
  publicPath: process.env.NODE_ENV === 'production' ? '/Go3.N/' : '/'
}
