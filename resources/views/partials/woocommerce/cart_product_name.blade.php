@if( WC_Subscriptions_Product::is_subscription($data['cart_item']['variation_id']) )
  @php $variation_object = wc_get_product_variation_attributes($data['cart_item']['variation_id']); @endphp
  {{ $variation_object['attribute_size'] }} {{ $data['name']  }}
@else
  <em class="qty" style="font-style: normal">
    {{ $data['cart_item']['quantity']  }}
  </em>
  {{ $data['name']  }}
  @if( $data['name'] === 'Willo Fit Finder Kit' && !isset($data['email']) )
  @include('partials.question-mark', ['content' => __('Once you update your size on our website, we’ll ship the Essential Set and your Fresh Routine Refills to you.','willo')])
  @endif
@endif
