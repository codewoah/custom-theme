
<h3 class="title">
  <a href="@php echo esc_url( wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink( 'myaccount' ) ) ) @endphp">
    <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
    Edit billing address
  </a>
</h3>
<div class="inner">
  @include('partials.woocommerce.account.edit-address' , ['type' => 'billing'])
</div>
