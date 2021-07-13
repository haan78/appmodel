const path = require("path");
const mode = process.env.NODE_ENV;

console.log("Mode: "+mode);
const pages = {
    main: "src/main.js",
    welcome: "src/welcome.js",
    test:{
        entry:"src/test.js",
        chunks: ['chunk-vendors', 'chunk-common', 'test'],
        template:"public/temp.php",
        filename:"test.php"
        /*template:"public/temp.php",
        title:"Test",
        filename:"test.php",
        inject:"head",
        favicon:"public/assets/favicon-96x96.png",
        chunks: ['chunk-vendors', 'chunk-common', 'test']*/
    }
};

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
