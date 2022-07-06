<div class="willo-product-detail__plan">
  {!! \App\Controllers\ShopPage::getText() !!}
@php
  if( $product->is_type('variable') ):
    $variations_attrs = $product->get_variation_attributes();
    $attribute_keys  = array_keys( $variations_attrs );
    $variations_json = wp_json_encode( $product->get_available_variations());
    $variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
  endif
@endphp

<form data-qty="1" id="willo_product_from" class="willo_product" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
  <div class="willo_product__row">
    <span class="willo_product__row__title">
      Choose your subscription plan
      @include('partials.question-mark', ['content' => 'Refill subscription plans are for ages 6 and up. Savings will increase with each added member up to 5 per plan.'])
    </span>
    @if( $product->is_type('variable') )
      @include('partials.woocommerce.product_plan')
    @endif
  </div>
  <div class="willo_product__row">
    <span class="willo_product__row__title">
      Choose plan payment frequency — Pay later
      @include('partials.question-mark', ['content' => 'Your subscription plan be activated and you’ll get charged once your entries order is shipped.'])

    </span>
    @if( $product->is_type('variable') )
    @include('partials/woocommerce/product_frequency')
    @endif
  </div>

  @php
    do_action('woocommerce_before_add_to_cart_button', absint( $product->get_id() ));
    do_action('willo_add_cart_btn', \App\sage('config')->get('theme')['side_product_add_cart']['essential_set'])
  @endphp
</form>
</div>

