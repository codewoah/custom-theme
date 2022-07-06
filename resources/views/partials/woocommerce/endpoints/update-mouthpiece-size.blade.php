<h3 class="title">
  <a href="@php echo esc_url( wc_get_endpoint_url( 'orders',  '', wc_get_page_permalink( 'myaccount' ) ) ) @endphp">
  <span class="back"><img src="@php echo \App\asset_path('images/back-arrow.svg') @endphp" alt=""></span>
  Update mouthpiece size
  </a>
</h3>
<div class="order-mouthpiece-size">
  <div class="order-mouthpiece-size__left size">
    @if( $order && $order->get_meta('_mouth_sizes') )
      @foreach( $order->get_meta('_mouth_sizes') as $i => $member )
        <div class="size__item" data-index="{{$i}}">
          <input type="text" placeholder="Member’s name @php echo $i+1 @endphp" value="{{$member['name']}}">
          @php
          /** @var WC_Product_Variable $p */
            $p = wc_get_product(get_field('fresh_routine_kit','option'));
            $sizes = [];
            foreach ($p->get_variation_attributes() as $size) {
                $sizes[] = $size;
            }


          @endphp
          @include(
            'partials.select-ui',
            [
                'value' => $member['size'],
                'label' => 'Mouthpiece size*',
                'options'=> $sizes[0]
            ]
          )
        </div>
      @endforeach
    @endif
    <div class="size__update">
      <span>Make sure it’s sized to you</span>
      <p>Find your exact match with Willo Fit Finder Kit and ensure the right selection of mouthpiece size arrive before your order.</p>
      <span class="flex flex--a-center hide-checkbox">
        <input id="confirm_size_updates" type="checkbox">
        <label for="confirm_size_updates">I have matched all the members and their sizes accordingly to Willo Fit Finder Kit.</label>
      </span>
      <button disabled type="button" class="btn btn--blue center" id="submit_size_updates">Submit</button>
      <input type="hidden" id="update_size_oder_id" value="{{$order_id}}">
    </div>
  </div>
  <div class="order-mouthpiece-size__right">
    <img src="@php echo \App\asset_path('images/illustration.svg') @endphp" alt="">
    <small>*Refer to Willo Fit Finder Kit</small>
  </div>
</div>
