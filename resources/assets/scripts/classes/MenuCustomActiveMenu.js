export default class MenuCustomActiveMenu {
  constructor(
    config
  ) {
    this.detectParentActive(config)
    this.displayActiveMenuOnMobile()
    this.displaySubMenuMobile('.mobile-active-menu')
  }

  detectParentActive(config) {
    const path_name = window.location.pathname;
    Object.keys(config).forEach( (endpoint, index) => {
      if( path_name.includes(endpoint) ) {
        if(document.querySelector(config[endpoint])) {
          document.querySelector(config[endpoint]).classList.add('is-active')
        }
      }
    })
  }

  displayActiveMenuOnMobile() {
    const active_account_menu =  document.querySelector('.woocommerce-MyAccount-navigation-link.is-active');
    if( active_account_menu ) {
      const textactive_menu = active_account_menu.querySelector('a').textContent
      if(textactive_menu !== '') {
        document.querySelector('.mobile-active-menu .mobile-active-menu__text').textContent = textactive_menu;
      }
    }
  }

  displaySubMenuMobile(selector) {
    if(document.querySelector(selector)) {
      document.querySelector(selector).addEventListener('click',()=>{
        document.querySelector(selector).parentElement.classList.toggle('active')
      })
    }
  }
}
