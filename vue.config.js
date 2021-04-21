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
            filename: '/css/[name].css',
            chunkFilename: '/css/[name].css'
        }
    },

    configureWebpack: {
        output: {
            filename: 'js/[name].js',
            chunkFilename: 'js/[name].js'
        }
    },

    chainWebpack: config => {
        
        config.plugins.delete('html-main').delete('prefetch-main').delete('preload-main');
        config.plugins.delete('html-welcome').delete('prefetch-welcome').delete('preload-welcome');
        config.devtool( mode == 'development' ? 'source-map' : false);
    }
}
