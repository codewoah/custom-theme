import MouthPieceSizeValidator from "../classes/MouthPieceSizeValidator";
import {cancelSubscription, reactivateSubscription, ReScheduleDelivery, trackOrder} from "../util/AjaxEvents";
import MenuCustomActiveMenu from "../classes/MenuCustomActiveMenu";
import FormBillingShippingSubmitter from "../classes/FormBillingShippingSubmitter";
import FormEditProfileSubmitter from "../classes/FormEditProfileSubmitter";
export default {
  init() {
    // JavaScript to be fired on the checkout page
    new MouthPieceSizeValidator({
      fields_wrapper : '.order-mouthpiece-size__left',
      field_validate : '.size__item'
    });

    if( document.querySelector('.adress-form-submitter') ) {
      new FormBillingShippingSubmitter([
        '.adress-form-submitter',
      ])
    }

    if( document.querySelector('#edit-user-profile') ) {
      new FormEditProfileSubmitter([
        '#edit-user-profile',
      ])
    }

    new MenuCustomActiveMenu({
      'edit-address-billing': '.woocommerce-MyAccount-navigation-link--edit-address',
      'edit-address-shipping': '.woocommerce-MyAccount-navigation-link--edit-address',
      'modify-subscription-plan':  '.woocommerce-MyAccount-navigation-link--subscriptions',
      'order-update-mouthpiece-size':  '.woocommerce-MyAccount-navigation-link--orders',
      'payment-methods':  '.woocommerce-MyAccount-navigation-link--edit-address',
      'add-payment-method':  '.woocommerce-MyAccount-navigation-link--edit-address',
    })

    if( document.querySelector('.select2') ) {
      window.jQuery('.select2').select2()
    }

    document.querySelectorAll('.close_delivery-modal').forEach(close=>{
      close.addEventListener('click',()=>{
        document.querySelectorAll('.modal').forEach(modal => modal.classList.remove('visible'))
      })
    });

    if( document.getElementById('cancel-subscription-action') ) {
      document.getElementById('cancel-subscription-action').addEventListener('click', e =>{
        e.preventDefault();
        cancelSubscription(e.target.getAttribute('data-subscription'))
      });
    }

    if( document.getElementById('reactivate-subscription-action') ) {
      document.getElementById('reactivate-subscription-action').addEventListener('click', e =>{
        e.preventDefault();
        reactivateSubscription(e.target.getAttribute('data-subscription'))
      });
    }

    if( document.getElementById('edit-delivery-date') ) {
      document.getElementById('edit-delivery-date').addEventListener('click',e =>{
        e.preventDefault()
        if( document.getElementById('parent') ) {
          import('./../vendor/rome').then( module => {
            module.default(
              document.getElementById('parent'),
              {
                initialValue: document.getElementById('edit-delivery-date').getAttribute('data-next-payment'),
                min: document.getElementById('edit-delivery-date').getAttribute('data-next-payment'),
                max:document.getElementById('edit-delivery-date').getAttribute('data-max')
              }
            ).on('data', value =>{
               var today  = new Date(value);
              document.querySelector('.next-delivery-text strong').innerHTML = today.toLocaleDateString("en-US",{ year: 'numeric', month: 'long', day: '2-digit' })
              document.getElementById('save_new-delivery').setAttribute('data-new-date',value)
            });
          });

          document.getElementById('delivery-modal').classList.add('visible')
        }
      })
    }

    if( document.getElementById('cancel-subscription') ) {
      document.getElementById('cancel-subscription').addEventListener('click',e =>{
        e.preventDefault()
        document.getElementById('cancel-modal').classList.add('visible')
      })
    }

    if( document.getElementById('reactivate-subscription') ) {
      document.getElementById('reactivate-subscription').addEventListener('click',e =>{
        e.preventDefault()
        document.getElementById('reactivate-sub-modal').classList.add('visible')
      })
    }


    if( document.getElementById('save_new-delivery') ) {
      document.getElementById('save_new-delivery').addEventListener('click',() =>{
        if( document.getElementById('save_new-delivery').getAttribute('data-new-date') ) {
          ReScheduleDelivery(
            document.getElementById('save_new-delivery').getAttribute('data-new-date'),
            document.getElementById('save_new-delivery').getAttribute('data-subscription')
          )
        }
      })
    }

    document.querySelectorAll('.tracking-modal').forEach(modal_trigger=>{
      modal_trigger.addEventListener('click',e =>{
        e.preventDefault()
        trackOrder(modal_trigger.getAttribute('data-order-status'))
      })
    });

  },
};
