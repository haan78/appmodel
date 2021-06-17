const path = require("path");
const mode = process.env.NODE_ENV;

console.log("Mode: "+mode);

module.exports = {
    outputDir: path.resolve(__dirname, "dist"),

    pages: {
        main: "src/main.js",
        welcome: "src/welcome.js"
    },

    css: {
        extract: { 
            filename: 'assets/[name].css',
            chunkFilename: 'assets/[name].css'
        }
        /*extract:false*/
    },

    configureWebpack: {
        output: {
            filename: 'assets/[name].js',
            chunkFilename: 'assets/[name].js'
        }/*,
        optimization: {
            splitChunks: false
        }*/
    },

    chainWebpack: config => {
        
        config.plugins.delete('html-main').delete('prefetch-main').delete('preload-main');
        config.plugins.delete('html-welcome').delete('prefetch-welcome').delete('preload-welcome');
        config.module.rule('fonts').use('url-loader').loader('url-loader').tap(options => {
          // modify the options...
          options.fallback.options.name = 'assets/[name].[ext]'

          return options
        });
        config.devtool( mode == 'development' ? 'source-map' : false);
    }
}
