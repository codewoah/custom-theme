export default class shippingFieldValidator {

  not_valid = [];

  constructor() {
    this.fields_to_validate = document.querySelectorAll('.woocommerce-billing-fields__field-wrapper .validate-required')
  }

  validate() {

    this.fields_to_validate.forEach( field =>{
      if( field.classList.contains('woocommerce-invalid') ) {
        this.not_valid.push(field)
      }
    })

    return this.not_valid.length <= 0;
  }
}
