<?php
$email_heading = get_field('heading_title_processing_email','option');
$vars = array(
  '$customer_firstname' => $order->get_billing_first_name(),
);
do_action( 'woocommerce_email_header', $email_heading, $email );
?>
<h2 id="order-id">
  Order #{!! $order->get_id() !!}
</h2>
{!! strtr(get_field('processing_email_text_content','option'),$vars) !!}
<?php
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
?>
@include('woocommerce.emails.customer_infos')
<?php
do_action( 'woocommerce_email_footer', $email );
?>
