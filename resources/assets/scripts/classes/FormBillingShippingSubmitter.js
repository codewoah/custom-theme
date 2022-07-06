import {submitBillingOrShippingFormField} from "../util/AjaxEvents";

export default class FormBillingShippingSubmitter {

  form_error = []

  constructor(
    selectors
  ) {
    this.initEvents(selectors)
  }

  initEvents(selectors) {
    if(selectors) {
     selectors.forEach(form =>{
       document.querySelector(form).addEventListener('submit', e => this.submit(e))
      })
    }

  }

  submit(e) {
    e.preventDefault();
    const form = e.target;
    if(this.is_submitable(form)) {
      const form_id = form.getAttribute('id')
      submitBillingOrShippingFormField(
        window.jQuery(form).serialize(),
        form_id === 'edit-customer-shipping-address' ? 'shipping' : 'billing'
      )
    }
    // console.log)
  }

  is_submitable(form) {
    this.form_error = [];
    const requiredFields = form.querySelectorAll('.required');
    requiredFields.forEach( field =>{
      field.classList.remove('error')
      if( field.value === '' ) {
        this.form_error.push({
          field
        })
      }
    })

    if( this.form_error.length > 0 ) {
      this.display_bad_fields()
      return false
    }

    return true;

  }

  display_bad_fields() {
    this.form_error.map( error  =>{
      error.field.classList.add('error')
    });
  }
}
