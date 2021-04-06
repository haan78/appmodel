const path = require("path");
const mode = process.env.NODE_ENV;

console.log("Mode: "+mode);

module.exports = {
    outputDir: path.resolve(__dirname, "dist"),

    pages: {
        main: "src/main.js",
        login: "src/login.js"
    }
    , css: {
        extract: false,
    },
    configureWebpack: {
        optimization: {
            splitChunks: false
        },
        output: {
            filename: 'js/[name].js'
        }
    },
    chainWebpack: config => {
        config.plugins.delete('html-main').delete('prefetch-main').delete('preload-main');
        config.plugins.delete('html-login').delete('prefetch-login').delete('preload-login');
        config.devtool( mode == 'development' ? 'inline-source-map' : false);
    }
}
