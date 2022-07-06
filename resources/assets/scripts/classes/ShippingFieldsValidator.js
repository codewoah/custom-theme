export default class ShippingFieldsValidator {

  fields_hidden_on_start = [
    'billing_city_field',
    'billing_state_field',
    'billing_postcode_field',
    'billing_phone_field',
  ];

  validate_fields = [
    'billing_first_name',
    'billing_last_name',
    'billing_address_1'
  ];

  allFields =  [
    'billing_first_name',
    'billing_last_name',
    'billing_address_1',
    'billing_city',
    'billing_postcode',
    'billing_state',
    'billing_phone',
  ];

  can_show_rest_fields = false

  filled = [];

  constructor() {
    this.hideDefaultFields()
    this.inspectFields()
    this.allFields.forEach( field => {
      document.getElementById(field).addEventListener('keyup',() => {
        this.maybeDisplayNextStepsBtn()
      })

      window.jQuery( document.body ).on('change','#billing_state', e =>{
        e.stopImmediatePropagation()
        this.maybeDisplayNextStepsBtn()
      })

    })

    this.maybeDisplayNextStepsBtn()
  }

  hideDefaultFields() {
    this.fields_hidden_on_start.forEach( field =>{
      document.getElementById(field).style.display = 'none'
    })
    this.can_show_rest_fields_fn()
  }

  show_hidden_fields() {
    this.fields_hidden_on_start.forEach( field =>{
      document.getElementById(field).style.display = 'block'
    })

  }

  inspectFields() {

    let error = [];

    this.validate_fields.forEach(field => {
      document.getElementById(field).addEventListener('keyup',e => {
        this.can_show_rest_fields_fn(e.target.value)
      })
    })

  }

  can_show_rest_fields_fn() {
    const error = [];
    this.validate_fields.forEach(field => {
      if( document.getElementById(field).value === '' ) {
        error.push('nope')
      }
    })

    if( error.length === 0 )
      this.show_hidden_fields()
  }

  maybeDisplayNextStepsBtn() {
    this.filled = []
    this.allFields.forEach( field => {
      this.can_show_next_steps_btn(field)
    })
  }

  can_show_next_steps_btn(field) {

    if( document.getElementById(field).value === '' ) {
      this.filled.push(field)
    }


    if( this.filled.length === 0 ) {
      document.querySelector('.checkout-billing-continue').style.display = 'block';
    } else {
      document.querySelector('.checkout-billing-continue').style.display = 'none';
    }
  }
}
