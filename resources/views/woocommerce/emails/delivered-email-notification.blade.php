@include('woocommerce.emails.email-heading',['title' => ' is now delivered !'])
<h2>
  Order #{!! $order->get_id() !!}
</h2>
<p>
  @include('woocommerce.emails.short-content')
</p>
@include('woocommerce.emails.tracking-link', ['title' => $order])
<?php
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_footer', $email );
?>
