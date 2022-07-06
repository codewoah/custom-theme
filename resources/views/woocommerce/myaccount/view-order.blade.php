@php
$notes = $order->get_customer_order_notes();
/** @var WC_Order $order */
$order = wc_get_order($order_id);
@endphp

<h3 class="title">
  <a href="@php echo esc_url( wc_get_endpoint_url( 'orders',  '', wc_get_page_permalink( 'myaccount' ) ) ) @endphp">
    <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
    Orders
  </a>
  <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
  Order #@php echo $order->get_id() @endphp
</h3>

<div class="inner">
  <table class="account-table" aria-describedby="account-table">
    <thead>
    <tr>
      <th id="product-desc">Product description</th>
      <th id="product-qty">Quantity</th>
      <th id="product-order-date">Order date</th>
      <th id="product-order-price"></th>
    </tr>
    </thead>
    <tbody class="view-order-tbody">
    @foreach($order->get_items() as $item)
      @if( $item->get_data()['subtotal'] !== '0' )
      @php
        $product = wc_get_product($item->get_product_id());
        $sub = wc_get_product($item->get_variation_id());

      @endphp
      <tr>
        <td class="account-table__product-des">
          <span class="flex flex--a-center thumb-item">
            <?php
            echo $product->get_image(); // PHPCS: XSS ok.
            ?>
            <div>
            {{ $item->get_data()['name']  }}
            @if( \App\is_subscription($product) && $sub )
              <em>
                {{ $sub->get_data()['attributes']['billing-period'] === 'Year' ? 'Annual' : 'Every 3 months'  }}
              </em>
            @endif
            </div>
          </span>
        </td>
        <td class="account-table__product-qty">
           <div class="flex flex--a-center">
          @if( \App\is_subscription($product)  && $sub )
            {{ $sub->get_data()['attributes']['size']  }}
          @else
            {{ $item->get_data()['quantity']  }}
          @endif
           </div>
        </td>
        <td class="account-table__product-order-data">
          @php echo wc_format_datetime( $order->get_date_created() ) @endphp
        </td>
        <td class="account-table__product-price">
          <div class="flex flex--a-center">
          @php echo wc_price($item->get_data()['subtotal']) @endphp
          </div>
        </td>
      </tr>
      @endif
    @endforeach
    </tbody>
    <tfoot id="view-order-tfoot">
    <tr>
      <td></td>
      <td></td>
      <th scope="row">Subtotal</th>
      <td>
        {!! wc_price($order->get_subtotal()) !!}
      </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <th scope="row">Shipping</th>
      <td>Free</td>
    </tr>

    @if($order->get_discount_total() > 0)
    <tr>
      <td></td>
      <td></td>
      <th scope="row">Discount</th>
      <td>
        - {!! wc_price($order->get_discount_total()) !!}
      </td>
    </tr>
    @endif
    <tr>
      <td></td>
      <td></td>
      <th scope="row">Grand total</th>
      <td>
        {!! wc_price($order->get_total()) !!}
      </td>
    </tr>
    </tfoot>
  </table>
  <div class="account-cards">
    @include('partials.woocommerce.account-card', [ 'title' => 'Payment Method', 'content' => 'user_card_info', 'from' => 'order' ])
    @include('partials.woocommerce.account-card', [ 'title' => 'Shipping address', 'content' => 'user_card_shipping_address', 'from' => 'order' ])
  </div>
</div>
