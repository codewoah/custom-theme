<div class="modal" id="cancel-modal">
  <div class="modal__backdrop"></div>
  <div class="modal__container">
    <h2>Cancel your subscription</h2>
    <div class="modal__container__cancel">
      <img src="@php echo \App\asset_path('images/benefit-deliver.svg') @endphp" alt="">
      <p>Thank you for being part of our Willo community,<br>we are sad to see you leave.</p>
      <p>Are you sure to cancel your subscription?</p>
    </div>
    <div class="modal__container__actions">
      <button id="cancel-subscription-action" class="btn btn--blue btn--blue--outline" data-subscription="{{$sub_id}}">Yes</button>
      <button  class="btn btn--blue close_delivery-modal" >NO, KEEP IT</button>
    </div>
  </div>
</div>


