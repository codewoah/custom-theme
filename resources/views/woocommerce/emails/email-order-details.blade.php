<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<div style="max-width:450px;margin: 40px auto;background: #fff; box-shadow: 10px 10px 0 0 #142cd2;padding: 0 20px">
  <h1>
    <?php echo $order->get_status() === 'processing' ? 'Order summary' : 'Items in this shipment'; ?>
  </h1>

  <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="0">
    <thead style="display:none">
    <tr>
      <th colspan="3" class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
      <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      $order,
      array(
        'show_sku'      => $sent_to_admin,
        'show_image'    => false,
        'image_size'    => array( 32, 32 ),
        'plain_text'    => $plain_text,
        'sent_to_admin' => $sent_to_admin,
      )
    );
    do_action('email_later_subscription_review', $order);
    ?>
    </tbody>
    @if( $order->get_total() > 0 )
    <tfoot>
    <?php
    $item_totals = $order->get_order_item_totals();

    if( $order->get_subtotal() <= 0 ) {
      unset($item_totals['cart_subtotal']);
    }



    if ( $item_totals   ) {
    $i = 0;
    foreach ( $item_totals as $total ) {
    $i++;
    ?>
    <tr class="{!! $i === count($item_totals) - 1 ? 'bottom-spaced' : '' !!}">
      <th class="td" scope="row" colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 1px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
      <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 1px;' : ''; ?>">
        <?php  echo wp_kses_post( $total['value'] ); ?>
      </td>
    </tr>
    <?php
    }
    }
    if ( $order->get_customer_note() ) {
    ?>
    <tr>
      <th class="td" scope="row"  style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
      <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
    </tr>
    <?php
    }
    ?>
    </tfoot>
      @endif
  </table>
</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
