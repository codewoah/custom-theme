<?php
$order_items_total = count($order->get_items());
$is_fit_finder_kit_only = \App\order_only_contains_fit_finder_kit($order);
switch ($order->get_status()) {
  case 'shipped':
    if( $is_fit_finder_kit_only ) {
      $email_heading = get_field('heading_title_shipped_email_ffk','option');
    } else {
      $email_heading = get_field('heading_title_shipped_email','option');
    }
    break;
  case 'out-delivery':
    if( $is_fit_finder_kit_only ) {
      $email_heading =  get_field('heading_title_out_delivery_email_ffk','option');
    } else {
      $email_heading = get_field('heading_title_out_delivery_email','option');
    }
      break;
  case 'delivered':
    if( $is_fit_finder_kit_only ) {
      $email_heading =  get_field('heading_title_delivered_email_ffk','option');
    } else {
      $email_heading = get_field('heading_title_delivered_email','option');
    }
    break;
  default:
    break;
}

do_action( 'woocommerce_email_header', $email_heading, $email );
?>
