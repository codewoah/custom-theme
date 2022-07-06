<?php
$customer_id = get_current_user_id();
$token = WC_Payment_Tokens::get_customer_default_token($customer_id);
if( $token )
{
    $card_info = $token->get_data();
?>

<span class="strong">{{ ucfirst( $card_info['card_type'] ) }} card</span>
<div class="flex">
  <span>Ending in {{ $card_info['last4'] }}</span>
  <span>Expires on {{ $card_info['expiry_month']  }}/{{ $card_info['expiry_year'] }}</span>
</div>

<div class="flex flex--a-center" style="margin-top: 20px">
  <span></span>
  <a href="{!!  esc_url( wc_get_endpoint_url( 'payment-methods', '' , wc_get_page_permalink( 'myaccount' ) ) ) !!}">edit</a>
</div>
<?php
}
?>
