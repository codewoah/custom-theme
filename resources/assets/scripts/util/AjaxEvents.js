import {WilloCartUpdated} from "./events";
import DomEvents from "./DomEvents";
import Timer from "../classes/Timer";
import {isMobile} from "./Helpers";

export function AddToCart(product) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_add_cart&product=${product}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if(data.success){
        if( document.body.classList.contains('woocommerce-checkout') ) {
          window.jQuery( document.body ).trigger( 'update_checkout' );
        } else {
          window.document.dispatchEvent(WilloCartUpdated);
        }
      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function SerchVariation(size, period) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_search_variation&size=${size}&period=${period}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if(data.success){
        AddToCart(data.data)
      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function AjaxSizeSubmit(data, order_id){
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_update_sizes&data=${data}&order_id=${order_id}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if(data.success){
        document.querySelector('.size__update').innerHTML = ""
        const span = document.createElement('span');
        span.textContent = willo_frontend.success_size_updated.title;

        const icon = document.createElement('img');
        icon.setAttribute('src',willo_frontend.success_size_updated.icon);

        const text = document.createElement('p');
        text.innerHTML = willo_frontend.success_size_updated.text;

        document.querySelector('.size__update').insertAdjacentElement('beforeend', span)
        document.querySelector('.size__update').insertAdjacentElement('beforeend', icon)
        document.querySelector('.size__update').insertAdjacentElement('beforeend', text)

      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function ValidatePionerCode(code) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_validate_pionner_code&code=${code}`
  })
    .then((res) => res.json())
    .then(function(data) {
      document.querySelector('.enter-pioneer-code__submit').removeAttribute('disabled')

      if(data.success){


        if( document.querySelector('.side-cart__content .action .error') ) {
          document.querySelector('.side-cart__content .action .error').remove()
        }

        if( document.body.classList.contains('single-product') ) {
          document.getElementById('willo_product__add-to-cart').click();
        } else {
          window.location.reload();
        }

      }else {
        if( document.querySelector('.side-cart__content .error') ) {
          document.querySelector('.side-cart__content .error').remove()
        }
        const error = document.createElement('span')
        error.classList.add('error')
        error.textContent = data.data

        if( data.data === 'Pioneer code is invalid, please check your invitation email' ) {
          document.getElementById('enter-pioneer-code').appendChild(error)
          document.getElementById('enter-pioneer-code').querySelector('input').classList.add('invalid')
          document.getElementById('enter-pioneer-code').querySelector('small').style.display = 'none'
        }
        // document.querySelector('.side-cart__content .action').appendChild(error);

      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function ReScheduleDelivery(new_date,subscription) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_edit_delivery_date&date=${new_date}&subscription=${subscription}`
  })
    .then((res) => res.json())
    .then(function(data) {
      window.location.href = data;
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function PioneerModal( target ) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=open_pioneer_modal&target=${target}`
  })
    .then((res) => res.text())
    .then(function(data) {
      const side_bar_cart = document.querySelector('.side-cart');
      const body = document.querySelector('body')
      body.classList.add('fade_site')
      side_bar_cart.classList.add('show')
      side_bar_cart.querySelector('.side-cart__content').innerHTML = data
      if( !isMobile.any() ) {
        setTimeout(() => {
          if (side_bar_cart.querySelector('.side-cart__content #enter-pioneer-code input') || side_bar_cart.querySelector('.side-cart__content #join-pioneer-list input')) {
            if (target === 'pioneer-code') {
              side_bar_cart.querySelector('.side-cart__content #enter-pioneer-code input').focus()
            } else {
              side_bar_cart.querySelector('.side-cart__content #join-pioneer-list input').focus()
            }
          }

        }, 100)
      }

      DomEvents.dynamicEvents()
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function SignUp(login, password, subscribe) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=willo_signup_customer&login=${login}&password=${password}&subscribe=${subscribe}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if(data.success){
        window.location.reload();
      } else {
          if( document.querySelector('.login-form__error') ) {
            document.querySelector('.login-form__error').remove()
          }
          const error = document.createElement('div')
          error.classList.add('login-form__error')
          error.innerHTML = data.data
          document.getElementById('checkout-hello-form').insertAdjacentElement('afterend', error);
      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function applyCoupon(code) {
  const form = document.getElementById('checkout-hello-form');
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=apply_coupon_cart&code=${code}`
  })
    .then((res) => res.json())
    .then(data =>{
      if( data.success ) {
        if( document.body.classList.contains('single-product') ) {
          recalculateCart()
        } else {
          window.jQuery( document.body ).trigger( 'update_checkout', { update_shipping_method: false })
        }
      } else {
        window.jQuery(document).find('.order-sep').addClass('coupon')

        const error = document.createElement('span')
          error.classList.add('error')
          error.textContent =  data.data
          if( document.body.classList.contains('single-product') ) {
            document.getElementById('discount_input_wrapper').appendChild(error)
          } else {
            document.querySelector('.willo-checkout #discount_input_wrapper').appendChild(error)
          }
      }
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function removeCoupon(code) {
  const form = document.getElementById('checkout-hello-form');
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=remove_coupon_cart&code=${code}`
  })
    .then((res) => {
      res.json()
    })
    .then(data =>{
      recalculateCart()
    })
    .catch(function(error) {
      console.log(JSON.stringify(error));
    });
}

export function recalculateCart() {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=recalculate_cart`
  })
    .then((res) => res.text())
    .then(function(data) {
      const side_bar_cart = document.querySelector('.side-cart');
      side_bar_cart.querySelector('.side-cart__content').innerHTML = data
      DomEvents.dynamicEvents()
    })
    .catch(function(error) {
    });
}

export function cancelSubscription(sub_id) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=cancel_subscription&subscription=${sub_id}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if( data.success ) {
        // Simulate an HTTP redirect:
        window.location.replace(data.data);
      }
    })
    .catch(function(error) {
    });
}

export function reactivateSubscription(sub_id) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=reactivate_subscription&subscription=${sub_id}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if( data.success ) {
        // Simulate an HTTP redirect:
        window.location.replace(data.data);
      }
    })
    .catch(function(error) {
    });
}

export function submitBillingOrShippingFormField(data, type) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=edit_billing_shipping_addresses&${data}&type=${type}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if( typeof data === 'number' ) {
        window.location.reload();
      }
    })
    .catch(function(error) {
    });
}

export function submitEditProfileForm(data) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=edit_user_profile&${data}`
  })
    .then((res) => res.json())
    .then(function(data) {
      if(data.success) {
        window.location.reload()
      } else {
        const form = document.getElementById('edit-user-profile')
        const error = document.createElement('span')
        error.classList.add('error')
        error.style.textAlign = "center";
        error.style.marginTop = "16px";
        error.textContent = data.data;
        form.querySelector('button').insertAdjacentElement('afterend', error)
      }
    })
    .catch(function(error) {
    });
}


export function emptyCart() {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=empty_cart`
  })
    .then( () =>{
      const side_bar_cart = document.querySelector('.side-cart');
      const empty_message = document.createElement('h2');
      const body = document.querySelector('body')

      empty_message.textContent = 'You cart is empty'
      side_bar_cart.querySelector('.side-cart__content').innerHTML = ''
      side_bar_cart.querySelector('.side-cart__content').appendChild(empty_message)
      setTimeout(()=>{
        side_bar_cart.classList.remove('show')
        body.classList.remove('fade_site')
        Timer.clear_cart_interval()
      },1500)
    })
    .catch(function(error) {
    });
}

export function trackOrder(order_status) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=track_order&status=${order_status}`
  }).then((res) => res.text())
    .then( data =>{
      const modal =  document.getElementById('tracking-modal')
      modal.querySelector('.modal__container').innerHTML = data
      modal.classList.add('visible')
      modal.querySelector('.close_delivery-modal').addEventListener('click',()=>{
        modal.classList.remove('visible')
      })
  }).catch(function(error) {
  });
}

export function klaviyoOptinNewsletter(email, from) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=klaviyo_optin_newsletter&email=${email}`
  }).then((res) => res.json())
    .then( data =>{
      if( data.success ) {
        const event = new CustomEvent('klaviyo_optin_newsletter_success', { 'detail': {
          email,
          from
        }});

        window.document.dispatchEvent(event)

      }
    }).catch(function(error) {
  });
}

export function klaviyoStoreHowHeard(response, order_id) {
  fetch(willo_frontend.ajax_url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
    body: `action=klaviyo_store_how_heard&response=${response}&order_id=${order_id}`
  }).then((res) => res.json())
    .then( data =>{
      if( data.success ) {
        window.location.href = willo_frontend.home_url;
      }
    }).catch(function(error) {
  });
}
