@php $slider_blue_content = OptionPage::getSliderFields(); @endphp
<div class="slider">
  <div class="inner">
    <div class="swiper-container swiper-container-initialized slider__reviews  swiper-container-horizontal">
      <div class="swiper-wrapper ">
        @if((!empty($slider_blue_content)))
          @foreach($slider_blue_content as $review)
            <div class="swiper-slide slider__review">
              <div class="slider__review__grade">
                {!! $review['slider_blue_grade'] !!}
              </div>
              <div class="slider__review__comment">{{$review['slider_blue_comment']}}</div>
              <div class="slider__review__profil">
                <div class="profil__avatar">
                  <img src="{!! $review['slider_blue_avatar']['sizes']['shop_thumbnail'] !!}" srcset="{!! $review['slider_blue_avatar']['sizes']['shop_thumbnail'] !!}" alt="" />
                </div>
                <div class="profil__name">
                  {!! $review['slider_blue_user'] !!}
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
      <!--Arrows -->
      <div class="swiper-button-prev">
        <svg width="13px" height="24px" viewBox="0 0 13 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g id="slider-next-arrow" transform="translate(6.500000, 12.000000) scale(-1, 1) translate(-6.500000, -12.000000) " fill="#FFFFFF" fill-rule="nonzero">
              <path d="M0.496,2.966 C-0.141,2.31 -0.165,1.22 0.44,0.534 C1.047,-0.154 2.055,-0.181 2.691,0.474 L12.543,10.774 C13.171,11.483 13.147,12.608 12.491,13.286 L2.691,23.523 C2.056,24.18 1.048,24.155 0.441,23.468 C-0.167,22.782 -0.145,21.693 0.491,21.036 L8.632,12.385 C8.835,12.169 8.835,11.833 8.632,11.617 L0.496,2.966 Z" id="Path"></path>
            </g>
          </g>
        </svg>
      </div>
      <div class="swiper-button-next">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="24" viewBox="0 0 13 24">
          <path fill="#FFF" d="M.496 2.966C-.141 2.31-.165 1.22.44.534c.607-.688 1.615-.715 2.251-.06l9.852 10.3c.628.709.604 1.834-.052 2.512l-9.8 10.237c-.635.657-1.643.632-2.25-.055-.608-.686-.586-1.775.05-2.432l8.141-8.651c.203-.216.203-.552 0-.768L.496 2.966z"/>
        </svg>
      </div>
      <!-- Bullet Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
</div>
