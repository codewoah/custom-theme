@php
  $next_deliveries = $subscription->get_meta('_next_deliveries');
  $today = time();
@endphp
<h3 class="title">
  <a href="@php echo esc_url( wc_get_endpoint_url( 'view-subscription',   $subscription->get_id(), wc_get_page_permalink( 'myaccount' ) ) ) @endphp">
    <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
    Subscription #SUB@php echo $subscription->get_id() @endphp
  </a>
  <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
  Modify plan
</h3>
<div class="inner">
  <div class="white-box">
    <p>Thanks for being part of Willo community <br>since December 5, 2019 </p>
    <div class="flex">
      <div class="white-box__left">
        <p>Mouthpiece sizes</p>
      </div>
      <div class="white-box__right">
        <div class="white-box__right__wrapper">
          <span class="white-box__right__wrapper__title">Benefits of your plan</span>
          <div class="white-box__right__wrapper__benefits benefits">
            @include('partials.woocommerce.benefits-account-tpl',['image' => \App\asset_path('images/benefit-delivery.svg'), 'text' => __('Refills delivered at your convenience')])
            @include('partials.woocommerce.benefits-account-tpl',['image' => \App\asset_path('images/benefit-app.svg'), 'text' => __('Exclusive access to Willo mobile app')])
            @include('partials.woocommerce.benefits-account-tpl',['image' => \App\asset_path('images/benefit-support.svg'), 'text' => __('Dedicated support team 24/7')])
            @include('partials.woocommerce.benefits-account-tpl',['image' => \App\asset_path('images/benefit-money-back.svg'), 'text' => __('30 days return policy')])
          </div>
        </div>
        <div class="links">
          @php
            $next_deliveries = $subscription->get_data()['billing_period'] === 'year' ? \App\next_delivery_for_annual_plan($today,$next_deliveries,$subscription,false) : esc_attr( strtotime($subscription->get_date_to_display( 'next_payment' ) ) )
          @endphp
          <a href="#" id="edit-delivery-date" data-next-payment="@php echo date('Y-m-d', $next_deliveries)  @endphp">Edit delivery date</a>
          <a href="#" id="cancel-subscription" data-sucription="{!! $subscription->get_id() !!}">Cancel subscription</a>
        </div>
      </div>
    </div>
  </div>
</div>

@include(
  'partials.woocommerce.account.delivery-modal',
  [
      'next_deliery' => $next_deliveries,
      'sub_id' => $subscription->get_id()
  ]
)
