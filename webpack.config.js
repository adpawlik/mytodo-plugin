const path = require('path'),
    settings = require('./settings');

module.exports = {
    entry: {
        App: settings.themeLocation + "assets/js/App.js"
    },
    output: {
        path: path.resolve(__dirname, settings.themeLocation + "assets/temp/scripts"),
        filename: "App.js"
    },
    module: {
        rules: [{
            test: /\.js$/,
            exclude: /node_modules/,
            use: {
                loader: 'babel-loader',
                options: {
                    presets: ['@babel/preset-env']
                }
            }
        }]
    },
    mode: 'development'
}