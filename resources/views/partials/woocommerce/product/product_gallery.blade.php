<div class="willo-product-detail__gallery">
  @php
  $attachment_ids = $product->get_gallery_image_ids();
  @endphp
  <div class="willo-product-detail__gallery__mini">
    @foreach($attachment_ids as $index => $attachment_id)
      <div data-big="@php echo wp_get_attachment_image_url( $attachment_id, 'large' )@endphp" class="willo-product-detail__gallery__mini__thumb @if($index===0) {{ 'active' }} @endif">
        @php echo wp_get_attachment_image( $attachment_id, 'thumbnail' ) @endphp
      </div>
    @endforeach
  </div>
  <div class="willo-product-detail__gallery__full">
    @php echo wp_get_attachment_image( $attachment_ids[0], 'large' )@endphp
  </div>
</div>
