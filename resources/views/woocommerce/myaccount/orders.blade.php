<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.7.0
 */
use function App\get_children_orders;
use function App\get_subscription_details_order;

defined( 'ABSPATH' ) || exit;

$query = new WC_Order_Query( array(
  'limit' => 5,
  'orderby' => 'date',
  'order' => 'DESC',
  'parent' => 0,
  'customer_id' => get_current_user_id(),
  'type' => 'shop_order'
) );

$orders = $query->get_orders();

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<h3 class="title">Orders</h3>
<div class="inner">
<?php if ( $orders ) : ?>
  <?php
  foreach ( $orders as $customer_order ) {
  $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
  $children_order = get_children_orders($customer_order);
  $item_count = $order->get_item_count() - $order->get_item_count_refunded();
  ?>
<table class="account-table" aria-describedby="account-table">
  <thead>
  <tr>
    <th id="order-id">Order #{{$order->get_id()}}</th>
    <th id="order-status">Status</th>
    <th id="order-date">Order date</th>
    <th id="order-total">Total</th>
    <th id="order-id"></th>
  </tr>
  </thead>

  <tbody>
    @foreach($children_order as $size_kit_order)
      @foreach($size_kit_order->get_items() as $size_kit_order_item )
        <tr class="order-line">
          <td class="order-line__product_name">@php echo $size_kit_order_item->get_data()['name'] @endphp</td>
          <td class="order-line__order-status">@php echo \App\willo_label_order_status($size_kit_order->get_status()) @endphp</td>
          <td class="order-line__order-date">@php echo date_i18n(get_option( 'date_format' ),$size_kit_order->get_date_created()) @endphp</td>
          <td class="order-line__order-price">@php echo wc_price($size_kit_order->get_total()) @endphp</td>
          <td class="center order-line__order-track">
            <a href="#" class="tracking-modal" data-order="{!! $size_kit_order ? $size_kit_order_item->get_id() : '' !!}" data-order-status="{!! $size_kit_order ? $size_kit_order->get_status() : '' !!}" style="display: block">Track order</a>
          </td>
        </tr>
      @endforeach
    @endforeach
    <tr class="order-line">
      <td class="order-line__product_name">
        @php
          $sub_details = get_subscription_details_order($order);
          if( wcs_order_contains_renewal($order) ) {
              echo sprintf(
                __('%d Fresh Routine Refills <br> <a href="%s">#SUB%s</a>'),
                $sub_details ? $sub_details['size_package'] : '',
                esc_url( wc_get_endpoint_url( 'view-subscription',  $sub_details ? $sub_details['id'] : '', wc_get_page_permalink( 'myaccount' ) ) ),
                $sub_details ? $sub_details['id'] : ''
              );
          }else {
              if( wcs_order_contains_renewal($order) ) {
                  echo sprintf(
                    __('The Essential Set + <br> %d Fresh Routine Refills <br> <a href="%s">#SUB%s</a>'),
                    $sub_details ? $sub_details['size_package'] : '',
                    esc_url( wc_get_endpoint_url( 'view-subscription',  $sub_details ? $sub_details['id'] : '', wc_get_page_permalink( 'myaccount' ) ) ),
                    $sub_details ? $sub_details['id'] : ''
                  );
              } else {
                  echo sprintf(
                    __('The Essential Set')
                                      );
              }

          }

        @endphp

      </td>
      <td class="order-line__order-status">
        @if(!\App\maybe_update_mouth_sizes($order) && false === wcs_order_contains_renewal($order))
          <div>
            <span></span>
            <a class="moutpiece-reminder" href="@php echo esc_url(wc_get_endpoint_url( 'order-update-mouthpiece-size',  $order->get_id(), wc_get_page_permalink( 'myaccount' ) )) @endphp">
              Mouthpiece size to update
              @include('partials.question-mark', ['content' => __("Simply input your mouthpiece size and we'll match it up to your outgoing order!.",'willo')])
            </a>
          </div>
        @else
          @php echo \App\willo_label_order_status($order->get_status()) @endphp
          @if( wcs_order_contains_renewal($order) )
{{--            <span class="renewal-notice">Renewal subscription</span>--}}
          @endif
        @endif
      </td>
      <td class="order-line__order-date">@php echo date_i18n(get_option( 'date_format' ),$order->get_date_created()) @endphp</td>
      <td class="order-line__order-price">
        @if( $order->get_subtotal() == 0 )
          @php echo 'FREE'; @endphp
        @else
          @php echo wc_price($order->get_total()) @endphp
        @endif
      </td>
      <td class="center order-line__order-track">
        <a href="#" class="tracking-modal" data-order="{!! $order->get_id() !!}" data-order-status="{!! $order->get_status() !!}" style="display: block">Track order</a>
        <a href="@php echo wc_get_endpoint_url( 'view-order', $order->get_id(), wc_get_page_permalink( 'myaccount' ) ) @endphp" style="display: block">View</a>
      </td>
    </tr>

  </tbody>
</table>
    <?php
    }
    ?>

<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
  <?php if ( 1 !== $current_page ) : ?>
  <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
  <?php endif; ?>

  <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
  <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php else : ?>
<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
  <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
    <?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
  </a>
  <?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
</div>
