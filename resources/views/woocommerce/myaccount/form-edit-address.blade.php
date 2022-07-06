<div class="inner">
<div class="account-addresses flex">
  <div class="box">
    <h3>Billing address</h3>
    <div class="box__content">
      @include('partials.woocommerce.account.user-billing-address')
    </div>
  </div>
  <div class="box">
    <h3>Shipping address</h3>
    <div class="box__content">
      @include('partials.woocommerce.account.user-shipping-address')
    </div>
  </div>
  <div class="box">
    <h3>Payment method</h3>
    <div class="box__content">
      @include('partials.woocommerce.account.user-default-payment-method')
    </div>
  </div>
</div>
</div>
