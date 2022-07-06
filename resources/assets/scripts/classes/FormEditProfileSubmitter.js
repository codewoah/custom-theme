import {submitEditProfileForm} from "../util/AjaxEvents";

export default class FormEditProfileSubmitter {

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
        document.querySelector(form).querySelectorAll('.required').forEach(field =>{
          field.addEventListener('keyup',e=>{
            if( field.value !== '' ) {
              field.classList.remove('error')
            }
          })
        })
      })

    }

  }

  submit(e) {
    e.preventDefault();
    const form = e.target;
    if(this.is_submitable(form)) {
      e.preventDefault();
      submitEditProfileForm(
        window.jQuery(form).serialize()
      );
    }
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
