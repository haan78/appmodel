const path = require("path");
const glob = require('glob');
const mode = process.env.NODE_ENV;

console.log("Mode: "+mode);
const pages = glob.sync('src/**.js').reduce(function(obj, el){
    obj[path.parse(el).name] = el;
    return obj
 },{});

module.exports = {
    outputDir: path.resolve(__dirname, "dist"),

    pages: pages,

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

        Object.keys(pages).forEach(key => {
            config.plugins.delete('html-'+key).delete('prefetch-'+key).delete('preload-'+key);
        });
        config.module.rule('fonts').use('url-loader').loader('url-loader').tap(options => {
          // modify the options...
          options.fallback.options.name = 'assets/[name].[ext]'

          return options
        });
        config.devtool( mode == 'development' ? 'source-map' : false);
    }
}
