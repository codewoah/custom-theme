<form name="checkout" method="post" class="checkout woocommerce-checkout" action="@php echo esc_url( wc_get_checkout_url() ); @endphp" enctype="multipart/form-data">
  <div class="willo-checkout">
    <div class="willo-checkout__steps steps">

        <span class="logo">
          <a href="@php echo home_url('/'); @endphp">
            <img src="{{ \App\asset_path('images/willo-logo-blue.svg') }}" alt="willo-logo">
          </a>
        </span>

      <div class="steps__wrapper">
        <span data-valid-step="@php is_user_logged_in() @endphp" class="@php echo !is_user_logged_in() ? 'active' : '' @endphp" data-view="login-form">Email</span>
        <span class="@php echo is_user_logged_in() ? 'active' : '' @endphp" data-view="checkout-shipping">Billing & Shipping</span>
        <span data-view="checkout-payment">Payment</span>
      </div>

      <div class="panel-view">
        @if( is_user_logged_in() )
          <div id="login-form" class="panel-view__view">
            @include('partials.woocommerce.checkout.steps.login-form')
          </div>
          <div id="checkout-shipping" class="panel-view__view panel-view__view--active">
            @php
                do_action('woocommerce_checkout_billing');
            @endphp
          </div>
          <div id="checkout-payment" class="panel-view__view">
            @include('partials.woocommerce.checkout.steps.checkout-payment')
          </div>
        @else
          <div id="login-form" class="panel-view__view panel-view__view--active">
          @include('partials.woocommerce.checkout.steps.login-form')
          </div>
          <div id="checkout-shipping" class="panel-view__view">
            @php
                do_action('woocommerce_checkout_billing');
            @endphp
          </div>
          <div id="checkout-payment" class="panel-view__view">
            @include('partials.woocommerce.checkout.steps.checkout-payment')
          </div>
        @endif
      </div>

    </div>
    <div class="willo-checkout__review review">
      <h2 class="review__title">
        Order summary
        <small>Shipping September 2020 — US only</small>
      </h2>
      @php do_action('woocommerce_checkout_order_review') @endphp
    </div>
  </div>
</form>
