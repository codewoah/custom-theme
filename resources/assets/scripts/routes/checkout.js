import CheckoutSteps from "../classes/CheckoutSteps";
import {SignUp} from "../util/AjaxEvents";
import ShippingFieldsValidator from "../classes/ShippingFieldsValidator";

export default {
  init() {



    document.addEventListener('keyup', e => {
      e.preventDefault();
      if(event.keyCode == 13){

        document.querySelectorAll('.login-form').forEach(view=>{
          if( window.jQuery(view).is(":visible") ) {
            window.jQuery(view).find('.login-form__submit button').click();
          }
        });

        if( document.querySelector('.checkout-billing-continue') ) {
          document.querySelector('.checkout-billing-continue').click()
        }
      }
    })

    const place_order_btn = window.jQuery( '#place_order' );

    window.jQuery( 'form.woocommerce-checkout' ).on( 'checkout_place_order', function() {
      document.getElementById('place_order').setAttribute('disabled', true);
    });

    window.jQuery( document.body ).on( 'checkout_error', function() {
      document.getElementById('place_order').removeAttribute('disabled');

    });

    // JavaScript to be fired on the checkout page
    new CheckoutSteps()

    new ShippingFieldsValidator()

  },
  finalize() {
    document.querySelectorAll('#signin-form__submit').forEach( login_submit => {
      login_submit.addEventListener('click',e => signIn(e))
    })

    document.querySelectorAll('#checkout-hello-form__submit').forEach( sign_up_form => {
      sign_up_form.addEventListener('click',e => signUp(e))
    })


    document.querySelectorAll('.login-form__wrapper input').forEach( input => {
      input.addEventListener('keyup',e=>{
        const email = e.target.parentElement.querySelector('.form__email').value;
        const password = e.target.parentElement.querySelector('.form__password').value;
        if( email !== '' && password !== '' ) {
          document.getElementById('signin-form__submit').style.display = 'block';
          document.getElementById('checkout-hello-form__submit').style.display = 'block';
        } else {
          document.getElementById('signin-form__submit').style.display = 'none';
          document.getElementById('checkout-hello-form__submit').style.display = 'none';

        }
      })

    })

  },
};

function signIn(e) {
  const form = e.target.parentElement.closest('#signin-form');
  const email    = form.querySelector('.form__email').value
  const password = form.querySelector('.form__password').value;
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_signin&login=${email}&password=${password}`
  }).then(res => {
    return res.json();
  })
    .then(function(data) {
      if(data.success){
        window.location.reload();
      } else {
        if( data.data === 'need_payment_for_renewal_process' ) {
          window.location.reload()
        }else {
          if( document.querySelector('.login-form__error') ) {
            document.querySelector('.login-form__error').remove()
          }
          const error = document.createElement('div')
          error.classList.add('login-form__error')
          error.innerHTML = data.data
          form.insertAdjacentElement('afterend', error);
        }

      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

function signUp(e) {
  const email = document.getElementById('checkout-hello-form__email').value;
  const password = document.getElementById('checkout-hello-form__password').value;
  const subscribe = document.getElementById('checkout-hello-form__suscribe').checked;

    console.log(email);
    SignUp(email,password,subscribe)
}
