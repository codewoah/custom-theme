<?php
use function App\order_only_contains_fit_finder_kit;
$order_items_total = count($order->get_items());
$is_fit_finder_kit_only = order_only_contains_fit_finder_kit($order);
$desc = '';
$vars = array(
  '$customer_firstname' => $order->get_billing_first_name(),
);
switch ($order->get_status()) {
  case 'shipped':
    if( $is_fit_finder_kit_only) {
      $desc =  strtr(get_field('shipped_email_text_content_ffk','option'), $vars) ;
      break;
    }
    elseif ( wcs_order_contains_renewal($order) ) {
      $desc = strtr(get_field('shipped_email_text_content_ffr','option'), $vars);
      break;

    }
    else {
      $desc = strtr(get_field('shipped_email_text_content','option'), $vars);
      break;

    }
  case 'out-delivery':
    if( $is_fit_finder_kit_only ) {
      $desc = strtr(get_field('out_delivery_email_text_content_ffk','option'), $vars);
      break;

    }
    elseif ( wcs_order_contains_renewal($order) ) {
      $desc = strtr(get_field('out_delivery_email_text_content_ffr','option'), $vars);
      break;

    }
    else {
      $desc = strtr(get_field('out_delivery_email_text_content','option'), $vars);
      break;

    }
  case 'delivered':
    if( $is_fit_finder_kit_only  ) {
      $desc =  strtr(get_field('delivered_email_text_content_ffk','option'), $vars);
      break;

    }
    elseif ( wcs_order_contains_renewal($order) ) {
      $desc = strtr(get_field('delivered_email_text_content_ffr','option'), $vars);
      break;

    }
    else {
      $desc = strtr(get_field('delivered_email_text_content','option'), $vars);
      break;

    }
  default:
    break;
}
?>

{!! $desc !!}
