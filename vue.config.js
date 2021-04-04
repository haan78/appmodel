const path = require("path");

module.exports = {
    outputDir: path.resolve(__dirname, "dist"),

    pages: {
        main: "src/main.js",
        login: "src/login.js"
    }
    , css: {
        extract: false,
        /*extract: { 
            filename: '/css/[name].[hash:8].css',
            chunkFilename: '/css/[name].[hash:8].css'
        }*/
    },
    configureWebpack: {
        optimization: {
            splitChunks: false
        },
        output: {
            filename: 'private/js/[name].js',
            //chunkFilename: 'js/[name].[hash:8].js',
        }
    },
    chainWebpack: config => {
        config.plugins
      .delete('html-main')
      .delete('prefetch-main')
      .delete('preload-main');
      config.devtool('inline-source-map');
    }
}
