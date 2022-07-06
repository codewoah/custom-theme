@php
  $statuses = [
    'processing' => 'Ordered',
    'shipped' => 'Shipped',
    'out-delivery' => 'Out for delivery',
    'delivered' => 'Delivered',
];
@endphp
<h2>Track your Fit Finder Kit</h2>
<div class="modal__container__cancel">
  <img src="{!! \App\asset_path('images/tracking-icon.svg') !!}" alt="">
  <p>Your Willo Fit Finder kit is on the way!</p>
  <p>Don’t forget to update your size and we'll match it <br>up to your outgoing order.</p>
  <div class="statuses">
    @foreach($statuses as $key => $label)
      <span class="{!! $key === $status ? 'active' : '' !!}">{{$label}}</span>
    @endforeach
  </div>
</div>
<div class="modal__container__actions">
  <button  class="btn btn--blue close_delivery-modal">GOT IT</button>
</div>
<small>Have a question? Reach us at <a href="mailto:support@willo.com">support@willo.com</a></small>
