// import external dependencies
import Swiper from './vendor/swiper.esm.browser.bundle.min.js';
// import local dependencies
import Router from './util/Router';
import common from './routes/common';

import home from './routes/home';
import aboutUs from './routes/about';
import checkout from './routes/checkout';
import singleProduct from './routes/Product';
import product from './routes/productPage';
import myAccount from './routes/Account';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  home,
  aboutUs,
  checkout,
  singleProduct,
  product,
  myAccount
});

// Load Events
window.addEventListener("DOMContentLoaded", () => {
  window.swiper = Swiper;
  routes.loadEvents()
});

