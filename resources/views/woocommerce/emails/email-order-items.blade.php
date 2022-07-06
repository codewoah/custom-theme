<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align  = is_rtl() ? 'right' : 'left';
$margin_side = is_rtl() ? 'left' : 'right';


foreach ( $items as $item_id => $item ) :
  /** @var WC_Product $product */
$product       = $item->get_product();
$sku           = '';
$purchase_note = '';
$image         = '';

if(
  !wcs_order_contains_subscription($order) &&
  \App\is_subscription($product->get_id())
) {
  continue;
}

if( \App\is_subscription($product->get_id()) ){
  $order->remove_item($item->get_id());
  $order->calculate_totals();
  continue;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
  continue;
}

if ( is_object( $product ) ) {
  $sku           = $product->get_sku();
  $purchase_note = $product->get_purchase_note();
  $image         = $product->get_image( $image_size );
}

?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
  <td class="td" colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
    <?php

    // Show title/image etc.
    if ( $show_image ) {
      echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
    }

    // Product name.
    echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

    // SKU.
    if ( $show_sku && $sku ) {
      echo wp_kses_post( ' (#' . $sku . ')' );
    }


    ?>
  </td>

  <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
    <?php echo $order->get_line_total($item) <= 0 ? '<span style="float: right;">Free</span>' :  wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
  </td>
</tr>
<?php

if ( $show_purchase_note && $purchase_note ) {
?>
<tr>
  <td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
    <?php
    echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
    ?>
  </td>
</tr>
<?php
}
?>

<?php endforeach; ?>
