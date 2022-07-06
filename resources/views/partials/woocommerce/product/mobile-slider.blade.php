@php
  $attachment_ids = $product->get_gallery_image_ids();
@endphp
<div class="willo-product-mobile-gallery">
<div class="mobile-product-slider">
  <div class="swiper-wrapper">
    @foreach($attachment_ids as $attachment_id)
      <div class="swiper-slide">
        @php echo wp_get_attachment_image( $attachment_id, 'large' ) @endphp
      </div>
    @endforeach
  </div>
  <!-- Add Pagination -->
  <div class="swiper-pagination"></div>
</div>

</div>

