<h3 class="title">Fresh Routine Refills subscription</h3>
<div class="inner">
  <table class="account-table" aria-describedby="account-table">
    <thead>
    <tr>
      <th id="subscription">Subscription</th>
      <th id="subscription-status">Status</th>
      <th id="subscription-next-delivery">Next delivery</th>
      <th id="subscription-next-payment">Next payment</th>
      <th id="subscription-total">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($subscriptions as $subscription)
      @php
        $next_deliveries = $subscription->get_meta('_next_deliveries');
        $today = strtotime( '-9 month', strtotime($subscription->get_date('next_payment')) );
      @endphp
      <tr class="order-line <?php echo esc_attr( $subscription->get_status() ); ?>">
        <td class="order-line__order-name">
          #SUB@php echo $subscription->get_order_number() @endphp
        </td>
        <td class="order-line__order-sub-status status--<?php echo esc_attr( $subscription->get_status() ); ?>">
          <span class="<?php echo esc_attr( $subscription->get_status() ); ?>"></span>
          <?php echo esc_attr( wcs_get_subscription_status_name( $subscription->get_status() ) ); ?>
        </td>
        <td class="next-delivery {{ \App\maybe_delivery_acive($subscription) }}">
          {{ $subscription->get_data()['billing_period'] === 'year' ? esc_attr( \App\next_delivery_for_annual_plan($today,$next_deliveries,$subscription) ) : esc_attr( $subscription->get_date_to_display( 'next_payment' ) ) }}
        </td>
        <td class="next-payment">
          <?php echo esc_attr( $subscription->get_date_to_display( 'next_payment' ) ); ?>
        </td>
        <td class="price">
            @php echo sprintf( __( '%s%s' ) , wc_price($subscription->get_total()), $subscription->get_data()['billing_period'] === 'year' ? '<span>/year</span>' : ' <span>every 3 months</span>' )  @endphp
          <span>
            <a href="<?php echo esc_url( $subscription->get_view_order_url() ) ?>">View</a>
          </span>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
