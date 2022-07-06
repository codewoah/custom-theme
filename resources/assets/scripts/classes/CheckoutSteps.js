import DomEvents from "../util/DomEvents";
import BillingFieldValidator from "../woocommerce/BillingFieldValidator";

export default class CheckoutSteps {

  activeView = 'login-form';

  constructor(props) {
    this.tryRenderNextView = this.tryRenderNextView.bind(this)
    document.querySelector('.steps__wrapper').querySelectorAll('span').forEach(element =>{
      element.addEventListener('click', ()=>{
        this.activeView =  element.getAttribute('data-view');
        this.tryRenderNextView(element)
      })
    })
  }

  /**
   *
   * @param element
   */
  tryRenderNextView(element) {
    if((
        this.activeView === 'checkout-shipping' &&
        document.querySelector('[data-view="login-form"]').getAttribute('data-valid-step'))
      || this.activeView === 'login-form')
    {
        window.location.reload();
    } else {


      document.querySelector('.next-steps').click()
      const validator = new BillingFieldValidator();
      if( validator.validate() ) {
        const current_view = document.querySelector('.panel-view__view--active');
        current_view.classList.remove('panel-view__view--active')
        if( document.querySelector('#'+this.activeView) ) {
          document.querySelector('#'+this.activeView).classList.add('panel-view__view--active')
        }
        this.set_ui_active_view(element);
      }
    }
  }

  set_ui_active_view(element) {
    document.querySelectorAll('.steps__wrapper span').forEach( span => span.classList.remove('active') )
    element.classList.add('active')

    document.querySelectorAll('form.checkout input[name="payment_method"]').forEach( payment =>{
      payment.addEventListener( 'change', ()=>{
        if(payment.value === 'klarna') {
          document.getElementById('place_order').classList.add('klarna_authorize')
        } else {
          document.getElementById('place_order').classList.remove('klarna_authorize')

        }
      })
    })

    window.jQuery('#shipping_state').select2()

    // if(document.querySelector('.shipping_address')) {
    //   document.querySelector('.shipping_address').style.display = 'none';
    //   document.getElementById('ship-to-different-address-checkbox').checked = false
    //   window.jQuery( document.body ).on( 'updated_checkout' , () =>{
    //       // window.jQuery('#shipping_state').select2();
    //       if( window.jQuery('.shipping_address').is(':visible') ) {
    //         document.getElementById('ship-to-different-address-checkbox').checked =  true
    //       }
    //   })
    //
    // }


  }

}
