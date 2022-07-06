@php
  $product = wc_get_product($product);
  $period = WC_Subscriptions_Product::get_period($product);
  $per_month = (int)$product->get_data()['price'] / ($period === 'year' ? 12 : 3);
  echo $product->get_price_html();
@endphp
