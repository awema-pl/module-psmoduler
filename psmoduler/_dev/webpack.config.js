var Encore = require('@symfony/webpack-encore');
const path = require('path');
const WebpackShellPlugin = require('webpack-shell-plugin');

Encore
    .setOutputPath(path.resolve('../views/'))
    .setPublicPath(path.resolve('../views/'))

    .addEntry('js/admin/sections/dashboard/index', './js/admin/sections/dashboard/index.js')

    .cleanupOutputBeforeBuild(['views/js', 'views/css', 'views/img', 'views/fonts', 'views/webfonts'], (options) => {
        options.verbose = true;
        options.root = __dirname;
        options.exclude = ['index.php'];
    })
    .enableSourceMaps(!Encore.isProduction())
;

if (Encore.isProduction()){
    Encore.addPlugin(new WebpackShellPlugin({
        onBuildStart: [],
        onBuildEnd: ['/bin/bash build-zip.sh']
    }))
}

module.exports = Encore.getWebpackConfig();
