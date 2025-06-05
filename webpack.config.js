const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/app.js')
    .addEntry('home', './assets/home.js')
    .addEntry('catalog', './assets/catalog.js')
    .addEntry('product', './assets/product.js')
    .addEntry('catalog_home', './assets/catalog_home.js')
    .addEntry('cart', './assets/cart.js')
    .addEntry('confirmation', './assets/confirmation.js')
    .addEntry('commandes', './assets/commandes.js')
    .addEntry('search', './assets/scss/search.scss')
    .addEntry('admin', './assets/js/admin.js')
    .addEntry('auth', './assets/scss/auth.scss')
    .addEntry('conditions', './assets/scss/pages/conditions.scss')
   
  
    

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    .enableSassLoader()
    .enableStimulusBridge('./assets/controllers.json')

    .configureImageRule({
        type: 'asset',
        maxSize: 8 * 1024
    })
;

module.exports = Encore.getWebpackConfig();