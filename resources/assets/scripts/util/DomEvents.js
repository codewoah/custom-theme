import BillingFieldValidator from "../woocommerce/BillingFieldValidator";
import {emptyCart, klaviyoOptinNewsletter, removeCoupon, ValidatePionerCode} from "./AjaxEvents";
import {enableScroll} from "./ScrollLock";
import {validateEmail} from "./Helpers";
import Timer from "../classes/Timer";

let typingTimer;                //timer identifier
const doneTypingInterval = 500;

class DomEvents {

  static dynamicEvents( ) {

    document.querySelectorAll('.join-waitlist').forEach(form=>{
      form.addEventListener('click',e=>{
        e.preventDefault();
        const email = document.getElementById('optin-email').value;
        klaviyoOptinNewsletter(email, 'form-waitlist')
      })
    });

    /** SingIn JS EVENT **/

    document.querySelectorAll('#empty_cart').forEach( trash_icon =>{
      trash_icon.addEventListener('click',e => {
        e.preventDefault()
        emptyCart()
      })
    })

    document.querySelectorAll('.order-review-mobile__header').forEach( header =>{
      header.addEventListener('click' ,e => {
        if(e.target.parentElement.nodeName !== 'A') {
          const review =  document.querySelector('.order-review-mobile__review');
          review.style.display = "block";
          header.style.display = 'none'
          // document.querySelector('.willo-checkout').style.marginTop = review.offsetHeight - 80 +'px';
          review.querySelector('.order-review-mobile__review__order').classList.add('order-review-mobile__review__order--reveal')
          const delay = 150;
          let i = 1;
          review.querySelectorAll('table tr.cart_item').forEach( item =>{
            setTimeout( () => item.classList.add('cart_item--animate'), delay * i )
            i++;
          });
        }

      })
    });

    document.querySelectorAll('.order-review-mobile__review__header').forEach( header =>{
      header.addEventListener('click' ,e => {
        if(e.target.parentElement.nodeName !== 'A') {

          const review = document.querySelector('.order-review-mobile__review');
          document.querySelector('.willo-checkout').style.marginTop = 0;
          document.querySelector('.order-review-mobile__header').style.display = 'flex';
          review.style.display = "none";
          review.querySelectorAll('table tr.cart_item').forEach(item => {
            item.classList.remove('cart_item--animate')
          });
        }
      });
    });

    document.querySelectorAll('.next-steps').forEach( nextStepBtn => {
      nextStepBtn.removeEventListener('click', this.nextSteps, true);
      nextStepBtn.addEventListener('click',e => this.nextSteps(e))
    })

    document.querySelectorAll('.side-cart__close').forEach( nextStepBtn => {
      nextStepBtn.removeEventListener('click', this.closeCart, true);
      nextStepBtn.addEventListener('click',e => this.closeCart(e))
    })

    document.querySelectorAll('.willo_product__row__frequency').forEach( nextStepBtn => {
      nextStepBtn.removeEventListener('click', this.selectFrequency, true);
      nextStepBtn.addEventListener('click',e => this.selectFrequency(e))
    })

    // if(document.querySelector('#select_ui_reveal')) {
    //   document.querySelector('#select_ui_reveal').addEventListener('click',e=>{
    //     document.querySelector('.select-ui__options').classList.toggle('show')
    //     e.target.classList.toggle('show')
    //   })
    // }

    document.querySelectorAll('.select-ui__options__option').forEach( element =>{
      element.addEventListener('click',e =>{
        const value = element.getAttribute('data-value');
        element.parentElement.previousElementSibling.querySelector('h3 small').textContent = 'Family of ' + value
        document.querySelector('.select-ui__selected').setAttribute('data-choice', value)
        document.querySelector('.select-ui__selected').click()
        document.querySelector('.select-ui__options').classList.remove('show')
      })

    })

    // document.querySelectorAll('.refill_qty_updater__minus').forEach( updater =>{
    //   updater.removeEventListener('click', this.decreaseCartRoutineQty, true);
    //   updater.addEventListener('click',e => this.decreaseCartRoutineQty(e))
    // })
    //
    // document.querySelectorAll('.refill_qty_updater__plus').forEach( updater =>{
    //   updater.removeEventListener('click', this.increaseCartRoutineQty, true);
    //   updater.addEventListener('click',e => this.increaseCartRoutineQty(e))
    // })

    window.jQuery('body').on('click','.refill_qty_updater__minus', e =>{
      e.stopImmediatePropagation()
      let currentQty = document.querySelector('.refill_qty_updater__value')
      let value = parseInt(currentQty.value);
      if( value > 1 ) {
        window.jQuery('.refill_qty_updater__value').val(value - 1);
        window.jQuery('.refill_qty_updater__value').trigger('change');
      }

    })

    window.jQuery('body').on('click','.refill_qty_updater__plus', e =>{
      e.stopImmediatePropagation()
      let currentQty = document.querySelector('.refill_qty_updater__value')
      let value = parseInt(currentQty.value);
      console.log(value)
      if( value < 5 ) {
        window.jQuery('.refill_qty_updater__value').val(value + 1);
        window.jQuery('.refill_qty_updater__value').trigger('change');
      }

    })


    window.jQuery('body').on('change','.refill_qty_updater__value', e => {
      const event = new CustomEvent('willo-cart-qty-updated',{
        "detail": {
          'size': e.target.parentElement.querySelector('input').value,
          'period' : e.target.parentElement.querySelector('input').getAttribute('data-period')
        }
      })

      clearTimeout(typingTimer);
      typingTimer = setTimeout(()=>{
        window.document.dispatchEvent(event);
      }, doneTypingInterval);
    })

    // document.querySelectorAll('.refill_qty_updater__value').forEach( qtyValueField =>{
    //   qtyValueField.removeEventListener('change', this.updateCartRoutineQty, true);
    //   qtyValueField.addEventListener('change',e => this.updateCartRoutineQty(e))
    // })

    document.querySelectorAll('.refill_qty_updater__value').forEach( qtyValueField =>{
      qtyValueField.removeEventListener('keyup', this.updateCartRoutineQty, true);
      qtyValueField.addEventListener('keyup',e => this.updateCartRoutineQty(e))
    })

    document.querySelectorAll('.input-select-ui').forEach(select_ui =>{
      select_ui.addEventListener('click',() =>{
        select_ui.classList.toggle('input-select-ui--active')
      })
    });

    document.querySelectorAll('.input-select-ui__options__option').forEach(select_ui_option =>{
      select_ui_option.addEventListener('click',e =>{

        select_ui_option.parentElement.previousElementSibling.textContent = select_ui_option.textContent;
        const event = new CustomEvent('mouthpiece-size-change',{ 'detail': {
            'value':  select_ui_option.textContent,
            'target': select_ui_option.closest('.size__item')
        }});
        window.document.dispatchEvent(event)
      })
    });

    if( document.getElementById('enter-pioneer-code') ) {
      document.getElementById('enter-pioneer-code').addEventListener('submit',e =>{
        e.preventDefault();
        document.querySelector('.enter-pioneer-code__submit').click();
      })
      document.getElementById('enter-pioneer-code').querySelector('input').addEventListener('focus',e =>{
        if(e.target.value !== '') {
          if( document.querySelector('.enter-pioneer-code__submit') ) {
            document.querySelector('.enter-pioneer-code__submit').remove()
          }
          const submitter = document.createElement('button')
          submitter.classList.add('btn')
          submitter.classList.add('btn--blue')
          submitter.classList.add('enter-pioneer-code__submit')
          submitter.textContent = 'LET\'S WILLO';
          document.querySelector('.side-cart__content').querySelector('.action').appendChild(submitter)
          submitter.addEventListener('click',()=> {
            submitter.setAttribute('disabled', true);
            ValidatePionerCode(e.target.value)
          })

        }
      });
      document.getElementById('enter-pioneer-code').querySelector('input').addEventListener('keyup',e =>{
        if(e.target.value !== '') {
          if( document.querySelector('.enter-pioneer-code__submit') ) {
            document.querySelector('.enter-pioneer-code__submit').remove()
          }
            const submitter = document.createElement('button')
            submitter.classList.add('btn')
            submitter.classList.add('btn--blue')
            submitter.classList.add('enter-pioneer-code__submit')
            submitter.textContent = 'LET\'S WILLO';
            document.querySelector('.side-cart__content').querySelector('.action').appendChild(submitter)
            submitter.addEventListener('click',()=> {
              submitter.setAttribute('disabled', true);
              ValidatePionerCode(e.target.value)
            })

        }
      });
    }

    if( document.getElementById('join-pioneer-list') ) {
      document.getElementById('join-pioneer-list').addEventListener('submit',e =>{
        e.preventDefault();
        document.querySelector('.enter-pioneer-code__submit').click();
      })
      document.getElementById('join-pioneer-list').querySelector('input').addEventListener('keyup',e =>{
        if(e.target.value !== '') {
          if( document.querySelector('.enter-pioneer-code__submit') ) {
            document.querySelector('.enter-pioneer-code__submit').remove()
          }
            const submitter = document.createElement('button')
            submitter.classList.add('btn')
            submitter.classList.add('btn--blue')
            submitter.classList.add('enter-pioneer-code__submit')
            submitter.textContent = 'Join the waitlist';
            document.querySelector('.side-cart__content').querySelector('.action').appendChild(submitter)
            submitter.addEventListener('click',() => {
              if( validateEmail(e.target.value) ) {
                submitter.setAttribute('disabled', true);
                klaviyoOptinNewsletter(e.target.value, 'sidecart')
              }
            })

        }
      });
      document.getElementById('join-pioneer-list').querySelector('input').addEventListener('focus',e =>{
        if(e.target.value !== '') {
          if( document.querySelector('.enter-pioneer-code__submit') ) {
            document.querySelector('.enter-pioneer-code__submit').remove()
          }
          const submitter = document.createElement('button')
          submitter.classList.add('btn')
          submitter.classList.add('btn--blue')
          submitter.classList.add('enter-pioneer-code__submit')
          submitter.textContent = 'Join the waitlist';
          document.querySelector('.side-cart__content').querySelector('.action').appendChild(submitter)
          submitter.addEventListener('click',() => {
            if( validateEmail(e.target.value) ) {
              submitter.setAttribute('disabled', true);
              klaviyoOptinNewsletter(e.target.value, 'sidecart')
            }
          })

        }
      });
    }

    if(document.querySelector('.woocommerce-remove-coupon')) {
      document.querySelector('.woocommerce-remove-coupon').addEventListener('click',e=>{
        e.preventDefault();
        removeCoupon(document.querySelector('.woocommerce-remove-coupon').getAttribute('data-coupon'))
      })
    }

  }



  static nextSteps(e) {
    if( e.target.classList.contains('checkout-billing-continue') ) {
      window.jQuery('form.checkout .input-text, select, input:checkbox').trigger('validate')
      const validator = new BillingFieldValidator();
      if( validator.validate() ) {
        document.querySelector('.steps__wrapper .active').nextElementSibling.click();
      }
    }
  }

  static closeCart(e) {
    document.querySelector('.side-cart').classList.remove('show')
    document.body.classList.remove('fade_site')
    enableScroll()
    Timer.clear_cart_interval()
  }

  static selectFrequency(e) {
    const target = e.target.closest('.willo_product__row__frequency ');
    document.querySelectorAll('.willo_product__row__frequency').forEach(line =>line.classList.remove('willo_product__row__frequency--active'))
    target.classList.add('willo_product__row__frequency--active')
    target.querySelector('label').click()
  }

  static updateCartRoutineQty(e) {
    const event = new CustomEvent('willo-cart-qty-updated',{
      "detail": {
        'size': e.target.parentElement.querySelector('input').value,
        'period' : e.target.parentElement.querySelector('input').getAttribute('data-period')
      }
    })

    clearTimeout(typingTimer);
    typingTimer = setTimeout(()=>{
      window.document.dispatchEvent(event);
    }, doneTypingInterval);


  }

  static decreaseCartRoutineQty(e) {
    let currentQty = document.querySelector('.refill_qty_updater__value')
    let value = parseInt(currentQty.value);
    if( value > 1 )
      currentQty.value = value - 1;
    if ("createEvent" in document) {
      var evt = document.createEvent("HTMLEvents");
      evt.initEvent("change", false, true);
      currentQty.dispatchEvent(evt);
    }
    else {
      currentQty.fireEvent("onchange");
    }
  }

  static increaseCartRoutineQty(e) {

  }

}

export default DomEvents
