var Encore = require('@symfony/webpack-encore');
const path = require('path');
const WebpackShellPlugin = require('webpack-shell-plugin');

Encore
    .enableStylusLoader()

    .setOutputPath(path.resolve('dist'))
    .setPublicPath(path.resolve('dist'))

    .addEntry('js/admin/sections/representatives/index', './resources/js/admin/sections/representatives/index.js')
    .addStyleEntry('css/admin/sections/representatives/index', './resources/css/admin/sections/representatives/index.styl')
    
    .cleanupOutputBeforeBuild(['dist/js', 'dist/css', 'dist/img', 'dist/fonts', 'dist/webfonts'], (options) => {
        options.verbose = true;
        options.root = path.resolve('./');
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
