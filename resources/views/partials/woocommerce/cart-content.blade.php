<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */
defined( 'ABSPATH' ) || exit;
?>
@if( $target === 'pioneer-waitlist' )
  @include('partials.woocommerce.pioneer-program')
@else
  <h2>
    {!! sprintf(__('Your cart (%s items)'), \App\get_total_product_cart()) !!}
    <span class="timer"></span>
    <span  id="empty_cart">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 18 20">
          <g fill="none" fill-rule="evenodd" stroke="#C6C6C6" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5">
              <path d="M.179 3.388L17.433 3.388M15.675 3.785v15.1c0 .56-.464 1.014-1.038 1.014H2.975c-.573 0-1.038-.454-1.038-1.014v-15.1M3.957 3.385V3.33c0-1.733 1.454-3.15 3.23-3.15h3.24c1.775 0 3.228 1.417 3.228 3.15v.056M6.684 8.196L6.684 15.487M10.928 8.196L10.928 15.487"/>
          </g>
      </svg>
    </span>
  </h2>
  @include('partials.woocommerce.side-cart-content')
@endif

