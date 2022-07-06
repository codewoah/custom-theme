<div class="order-review-mobile">
  <div class="order-review-mobile__header">
    <a href="{!! home_url('/') !!}" class="order-review-mobile__header__logo">
      <img src="{{ \App\asset_path('images/willo-logo-blue.svg') }}" alt="willo-logo">
    </a>
    <span class="order-review-mobile__header__price right">
      <span class="right__quantity">
        {!! \App\get_total_product_cart()  !!}
      </span>
      <span class="right__price">
        @php
          echo WC()->cart->get_total()
        @endphp
        <img src="{{ \App\asset_path('images/back-arrow.svg') }}" alt="willo-logo">
      </span>
    </span>
  </div>
  <div class="order-review-mobile__review">
    <div class="order-review-mobile__review__header">
      Order summary
      <span>
        <img src="{{ \App\asset_path('images/back-arrow.svg') }}" alt="willo-logo">
      </span>
    </div>
    <div class="order-review-mobile__review__order">
      @php do_action('woocommerce_checkout_order_review') @endphp
    </div>
  </div>
</div>
