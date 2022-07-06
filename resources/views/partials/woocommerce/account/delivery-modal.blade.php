<div class="modal" id="delivery-modal">
  <div class="modal__backdrop"></div>
  <div class="modal__container">
    <h2>Edit your delivery date</h2>
    <div class="modal__container__calendar">
      <div id='parent'></div>
    </div>
    <p class="next-delivery-text">New estimate delivery date <br> <strong>@php echo date_i18n(get_option('date_format'),$next_deliveries); @endphp</strong></p>
    <div class="modal__container__actions">
      <button class="btn btn--blue btn--blue--outline close_delivery-modal">CANCEL</button>
      <button id="save_new-delivery" class="btn btn--blue" data-subscription="{{$sub_id}}">SAVE</button>
    </div>
    <p>Future orders will be rescheduled 3 months from <br>your new delivery date.</p>
  </div>
</div>


