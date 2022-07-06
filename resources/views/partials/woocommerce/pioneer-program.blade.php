<h3>Are you part of <br>Willo Pioneer Program?</h3>
<img class="bandeau" src="@php echo \App\asset_path('images/mouths-icon@2x.png') @endphp" alt="">
@if( \App\is_in_stock(App\sage('config')->get('theme')['side_product_add_cart']['essential_set']) )
<form action="" id="enter-pioneer-code">
  <h4>I’m in the Pioneer Program </h4>
  <input class="grey-border" type="text" placeholder="Enter your Pioneer code* ">
  <small>* Your pioneer code is in your invitation email</small>
</form>
@endif

<form action="" id="join-pioneer-list">
  <h4>Not part of the Pioneer Program?</h4>
  <p>Access to a purchase of Willo is currently on invitation-only. <br>Please join our waitlist and we’ll make sure to notify you as soon as Willo is ready!</p>
  <input class="grey-border" type="email" placeholder="Enter your email ">
</form>

<div class="action">

</div>
