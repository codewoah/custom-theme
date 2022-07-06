import {AjaxSizeSubmit} from "../util/AjaxEvents";

export default class MouthPieceSizeValidator {

  constructor({fields_wrapper, field_validate}) {
    this.fields_wrapperSelector = document.querySelector(fields_wrapper );
    this.field_validate = field_validate;
    this.fields = [];
    this.isValid = false;
    if( this.fields_wrapperSelector ) {
      this.fields_wrapperSelector.querySelectorAll(field_validate).forEach( field =>{
        field.querySelector('input').addEventListener('keyup', e => this.changeMemberName(e))
        this.fields.push({
          'name' : field.querySelector('input').value,
          'size' : field.querySelector('.input-select-ui__selected').textContent.trim()
        })
      })
    }

    window.document.addEventListener('mouthpiece-size-change', e => this.sizeChange(e))
    if(document.getElementById('confirm_size_updates'))
      document.getElementById('confirm_size_updates').addEventListener('change', e => this.validate())
    if(document.getElementById('submit_size_updates'))
      document.getElementById('submit_size_updates').addEventListener('click', e => this.updateSize())
  }

  changeMemberName(e) {
    const index = e.target.closest('.size__item').getAttribute('data-index');
    this.fields[index]['name'] = e.target.value
    this.validate()
  }

  sizeChange(e) {
    const index = e.detail.target.getAttribute('data-index');
    this.fields[index]['size'] = e.detail.value
    this.validate()
  }

  validate() {
    const validator = this.fields.filter( entry => entry.name === '' || entry.size === '')
    if( validator.length === 0 ) {
      const event = new Event('mouthpiece-size-valid');
      window.document.dispatchEvent(event)
      this.enableSubmit()
    }
  }

  enableSubmit() {
    if( document.getElementById('confirm_size_updates').checked === true ) {
      document.querySelector('.size__update button').removeAttribute('disabled');
    } else {
      document.querySelector('.size__update button').setAttribute('disabled', true);

    }
  }

  updateSize() {
    AjaxSizeSubmit(
      JSON.stringify(this.fields),
      document.getElementById('update_size_oder_id').value
    )
  }
}
