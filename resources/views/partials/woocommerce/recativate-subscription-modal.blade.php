<div class="modal" id="reactivate-sub-modal">
  <div class="modal__backdrop"></div>
  <div class="modal__container">
    <h2>Reactivate your subscription</h2>
    <div class="modal__container__cancel">
      <img src="@php echo \App\asset_path('images/benefit-deliver.svg') @endphp" alt="">
      <p>Thank you for being part of our Willo community,<br>we are sad to see you leave.</p>
      <p>Are you sure to cancel your subscription?</p>
    </div>
    <div class="modal__container__actions">
      <button class="btn btn--blue btn--blue--outline close_delivery-modal" >Cancel</button>
      <button id="reactivate-subscription-action" class="btn btn--blue" data-subscription="{{$sub_id}}">REACTIVATE</button>
    </div>
  </div>
</div>


