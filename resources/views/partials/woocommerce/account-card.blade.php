<div class="account-cards__card">
  <header class="account-cards__card__header">
    {{ $title  }}
  </header>
  <div class="account-cards__card__content">
    @switch($content)
      @case('user_card_info')
        @include('partials.woocommerce.account-card-content-payment-method')
      @break

      @case('user_card_shipping_address')
        @include('partials.woocommerce.account-shipping_address')
      @break

      @case('account_shipping_address')
        @include('partials.woocommerce.user-shipping_address')
      @break

      @default
      @break
    @endswitch
  </div>
</div>
