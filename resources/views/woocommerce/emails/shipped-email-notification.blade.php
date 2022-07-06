@include('woocommerce.emails.email-heading', ['title' => ' is now en route !'])
<h2 id="order-id">
  Order #{!! $order->get_id() !!}
</h2>
@include('woocommerce.emails.short-content')
@include('woocommerce.emails.tracking-link',['order' => $order])
<?php
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_footer', $email );
?>
