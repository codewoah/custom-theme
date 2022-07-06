@php
$current_user_id = get_current_user_id();
/** @var WC_Order $parent_order */
$parent_order = wc_get_order($subscription->get_parent_id());
$mouthpiece_size = $parent_order->get_meta('_mouth_sizes');
$_mouth_sizes_completed = $parent_order->get_meta('_mouth_sizes_completed');
$sub_items = $subscription->get_items();
$routine = array_shift($sub_items)->get_data();
if( $routine['variation_id'] !== 0 ) {
    $routine_atts = wc_get_product_variation_attributes( $routine['variation_id'] );
}
$next_deliveries = $subscription->get_meta('_next_deliveries');
$today = strtotime( '-9 month', strtotime($subscription->get_date('next_payment')) );
@endphp

<h3 class="title">
  <a href="@php echo esc_url( wc_get_endpoint_url( 'subscriptions',  '', wc_get_page_permalink( 'myaccount' ) ) ) @endphp">
    <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
    Subscription #SUB@php echo $subscription->get_id() @endphp
  </a>
</h3>

<div class="view-subscription">
  <header class="view-subscription__header">
    <div class="view-subscription__header__picture">
      <img src="@php echo \App\asset_path('images/fresh-routine-logo.svg') @endphp" alt="">
    </div>
    <div class="view-subscription__header__info">
      <h2>
        Fresh Routine Refills
        @if( $subscription->get_status() === 'active' || $subscription->get_status() === 'pending' )
          <span>
            @php
              echo sprintf(
              __('%s PLAN %s (%d PERSON)'),
              $subscription->get_data()['billing_period'] === 'year' ? 'ANNUAL' : 'MONTHLY',
              $routine_atts['attribute_size'] > 1 ? ' - FAMILY ' : '',
              $routine_atts['attribute_size']
              );
            @endphp
            @if($subscription->get_data()['billing_period'] === 'year')
            <em class="tag tag--mint">YOU SAVE 10%</em>
            @include('partials.question-mark', ['content' => 'You save 10% in comparison with 3–months subscription plan payment.'])
            @endif
          </span>
        @else
          <span class="tag tag--warning">Cancelled</span>

        @endif

      </h2>
      @if( $subscription->get_status() === 'active' || $subscription->get_status() === 'pending'  )
      @if( $_mouth_sizes_completed  )
        <p>
          @php
            echo sprintf(
            __('You get delivered every 3 months straight to your door and pay %s.'),
            $subscription->get_data()['billing_period'] === 'year' ? 'once a year' : 'every 3 months'
            );
          @endphp
        </p>
        <a href="@php echo esc_url( wc_get_endpoint_url( 'modify-subscription-plan', $subscription->get_id(), wc_get_page_permalink( 'myaccount' ) ) ) @endphp">Modify plan</a>
      @else
          <p>
            <img src="@php echo \App\asset_path('images/arrow-account-mouthsize.svg') @endphp" alt="">
            Looking forward to brushing your teeth with Willo?<br>
            Add your mouthpiece sizes right away to receive your entire order!
            <span class="flex flex--a-center">
              <a href="@php echo esc_url(wc_get_endpoint_url( 'order-update-mouthpiece-size',  $parent_order->get_id(), wc_get_page_permalink( 'myaccount' ) )) @endphp" class="btn btn--blue">UPDATE SIZE</a>
              <a href="@php echo esc_url( wc_get_endpoint_url( 'modify-subscription-plan', $subscription->get_id(), wc_get_page_permalink( 'myaccount' ) ) ) @endphp">Modify plan</a>
            </span>
          </p>
      @endif
      @else
        @if( $subscription->get_billing_period() === 'year' )
          <p>You will be delivered until the end of your annual membership</p>
        @endif
      @endif
    </div>
  </header>
</div>

<div class="inner">
  <table class="account-table" aria-describedby="account-table">
    <thead>
    <tr>
      <th id="su-refill-qty">Refill quantity</th>
      <th id="sub-mouthpiece">Mouthpiece size</th>
      <th id="sub-next-delivery">Next delivery date</th>
      <th id="sub-next-payment">Next payment date</th>
      <th id="sub-total">Total</th>
    </tr>
    </thead>
    <tbody>
      <tr class="order-line">
        <td class="order-line__order-name">
          @if($routine_atts && $subscription->get_status() === 'active')
            {{ $routine_atts['attribute_size']  }} Routine Refill Kits
          @endif
        </td>
        <td class="mouthpiece-size">
          @if($_mouth_sizes_completed && $subscription->get_status() !== 'cancelled')
            @foreach($mouthpiece_size as $size)
              <span>{{ $size['size'] }}</span>
            @endforeach
          @else
            @if( $subscription->get_status() !== 'cancelled' )
            <div class="notice-update-mouthsize">
              <span></span>
              <a href="@php echo esc_url(wc_get_endpoint_url( 'order-update-mouthpiece-size',  $parent_order->get_id(), wc_get_page_permalink( 'myaccount' ) )) @endphp">Size to be updated</a>
            </div>
            @endif
          @endif
        </td>
        <td class="next-delivery">
          {{ $subscription->get_data()['billing_period'] === 'year' ? esc_attr( \App\next_delivery_for_annual_plan($today,$next_deliveries,$subscription) ) : esc_attr( $subscription->get_date_to_display( 'next_payment' ) ) }}
        </td>
        <td class="next-payment">
          <?php echo esc_attr( $subscription->get_date_to_display( 'next_payment' ) ); ?>
        </td>
        <td>
          @if( $subscription->get_status() !== 'cancelled' )
            @php echo sprintf( __( '%s %s' ) , wc_price($subscription->get_total()), $subscription->get_data()['billing_period'] === 'year' ? 'year' : ' every 3 months' )  @endphp
          @endif
        </td>
      </tr>
    </tbody>
  </table>
  <div class="account-cards">
    @include('partials.woocommerce.account-card', [ 'title' => 'Payment Method', 'content' => 'user_card_info' ])
    @include('partials.woocommerce.account-card', [ 'title' => 'Shipping address', 'content' => 'user_card_shipping_address' ])
  </div>
</div>
