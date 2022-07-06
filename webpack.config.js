var Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('resources/dist/')
    // public path used by the web server to access the output path
    .setPublicPath('/wp-content/themes/willo/resources/dist')
    .setManifestKeyPrefix('')
    // only needed for CDN's or sub-directory deploy

  .copyFiles({
        from: './resources/assets/images',
        to: 'images/[path][name].[ext]',
    })
  .configureLoaderRule('fonts', (loaderRule) => {
    loaderRule.options.esModule = false;
  })
  .configureLoaderRule('images', (loaderRule) => {
    loaderRule.options.esModule = false;
  })
  .configureBabel(function(babelConfig) {
    babelConfig.plugins.push('syntax-dynamic-import');
  })
    // .addEntry('rome-js', './resources/assets/scripts/vendor/rome.js')
    .addEntry('main-js', './resources/assets/scripts/main.js')
    .addEntry('main-css', './resources/assets/styles/main.scss')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    .enableSassLoader()
    // .enableReactPreset()
;
// build the first configuration
const localconf = Encore.getWebpackConfig();

// Set a unique name for the config (needed later!)
localconf.name = 'localconf';

// reset Encore to build the second config
Encore.reset();

Encore
  // directory where compiled assets will be stored
  .setOutputPath('resources/dist/')

  // public path used by the web server to access the output path
  .setPublicPath('/willo/wp-content/themes/willo/resources/dist')
  .setManifestKeyPrefix('')
  // only needed for CDN's or sub-directory deploy
  .copyFiles({
    from: './resources/assets/images',
    to: 'images/[path][name].[ext]',
  })
  .configureLoaderRule('fonts', (loaderRule) => {
    loaderRule.options.esModule = false;
  })
  .configureLoaderRule('images', (loaderRule) => {
    loaderRule.options.esModule = false;
  })
  .configureBabel(function(babelConfig) {
    babelConfig.plugins.push('syntax-dynamic-import');
  })
  // .addEntry('rome-js', './resources/assets/scripts/vendor/rome.js')
  .addEntry('main-js', './resources/assets/scripts/main.js')
  .addEntry('main-css', './resources/assets/styles/main.scss')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())
  .enableSassLoader()
  // .enableReactPreset()
;
// build the first configuration
const devconf = Encore.getWebpackConfig();
devconf.name = 'devconf';

Encore.reset();

Encore
  // directory where compiled assets will be stored
  .setOutputPath('resources/dist/')
  // public path used by the web server to access the output path
  .setPublicPath('/wp-content/themes/willo/resources/dist')
  .setManifestKeyPrefix('')
  // only needed for CDN's or sub-directory deploy
  .copyFiles({
    from: './resources/assets/images',
    to: 'images/[path][name].[ext]',
  })
  .configureLoaderRule('fonts', (loaderRule) => {
    loaderRule.options.esModule = false;
  })
  .configureLoaderRule('images', (loaderRule) => {
    loaderRule.options.esModule = false;
  })
  .configureBabel(function(babelConfig) {
    babelConfig.plugins.push('syntax-dynamic-import');
  })
  // .addEntry('rome-js', './resources/assets/scripts/vendor/rome.js')
  .addEntry('main-js', './resources/assets/scripts/main.js')
  .addEntry('main-css', './resources/assets/styles/main.scss')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())
  .enableSassLoader()
// .enableReactPreset()
;
// build the first configuration
const prodconf = Encore.getWebpackConfig();
prodconf.name = 'prodconf';

module.exports = [localconf, devconf, prodconf];
