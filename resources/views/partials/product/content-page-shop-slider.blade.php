 <div class="shop-slider">
   <div class="shop__slider__tiles" ></div>
    <div class="shop__slider inner">
      <div class="swiper-wrapper">
        <div class="shop__slider__item swiper-slide">
          <div class="shop__slider__image">
            <img alt="" src="{{ \App\asset_path('images/fit-finder-kit.svg') }}" />
          </div>
          <div class="shop__slider__content">
            <div class="swiper-pagination-shop"></div>
            <div class="shop__slider__pagination shop__slider__pagination--active">
              <span class="shop__slider__index" data-index="1">01</span>
              <h4>Receive Fit Finder Kit</h4>
              <p>Once you’ve placed your order you’ll receive your Fit Finder Kit by mail. Using the kit to match you to your ideal mouthpiece size you’ll then be able to input your size online and the rest of your order will ship.</p>
            </div>
          </div>
        </div>
        <div class="shop__slider__item swiper-slide">
          <div class="shop__slider__image">
            <img alt="" src="{{ \App\asset_path('images/essential-set.svg') }}" />
          </div>
          <div class="shop__slider__content">
            <div class="swiper-pagination-shop"></div>
            <div class="shop__slider__pagination shop__slider__pagination--active">
              <span class="shop__slider__index" data-index="2">02</span>
              <h4>Willo Touches Down</h4>
              <p>Shortly after you’ve input your mouthpiece size online,
                you’ll receive your complete Willo Essential Set along with your first Routine Refills shipment containing your perfectly matched mouthpiece and 2 rinse pods.</p>
            </div>
          </div>
        </div>
        <div class="shop__slider__item swiper-slide">
          <div class="shop__slider__image">
            <img alt="" src="{{ \App\asset_path('images/illustration2.svg') }}" />
          </div>
          <div class="shop__slider__content">
            <div class="swiper-pagination-shop"></div>
            <div class="shop__slider__pagination shop__slider__pagination--active">
              <span class="shop__slider__index" data-index="3">03</span>
              <h4>Fresh Routine Refills</h4>
              <p>3 months into using Willo, and right when you need them, your refills will arrive at your door with a fresh mouthpiece and 2 new rinse pods.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="section-shop-slider clean-system bis" id="clean-system">
  @include('partials.section-title', ['mainTitle' => "The Clean Sequence", 'classes' => "bottom-left"])
  <div class="hidden" id="subcontainer"></div>
  <div id="clean-system-container" class="clean-system__wrapper" >
    <div class="clean-system__container" id="container">
      <div class="clean-system__photo" id="sidebar">
        <div class="sidebar__inner">
          <img class="clean-system__tiles" alt="" src="{{ \App\asset_path('images/tiles-background2@2x.png') }}" srcset="{{ \App\asset_path('images/tiles-background2@2x.png') }}"/>
          <img alt="" class="clean-system__photo__bg" src="{{ \App\asset_path('images/rectangles.svg') }}"/>
          <div class="clean-system__photo-inner">
            <img class="js-clean-system-image clean-system__image js-slice-image clean-system__image--active" alt="" src="{{ \App\asset_path('images/fit-finder-kit.svg') }}" title="01"/>
            <img class="js-clean-system-image clean-system__image js-slice-image" alt="" src="{{ \App\asset_path('images/essential-set.svg') }}" title="02"/>
            <img class="js-clean-system-image clean-system__image js-slice-image" alt="" src="{{ \App\asset_path('images/illustration2.svg') }}" title="03" />
          </div>
        </div>
    </div>
    <div class="clean-system__content" id="steps">
        <div class="clean-system__steps">
          <div class="clean-system__step-wrapper">
            <div class="js-clean-system-step clean-system__step sliced-scroll__item js-scroll-item" title="01">
              <span data-step="1" class="js-clean-system-nav-link clean-system-nav__item">01</span>
              <span class="clean-system__progress js-progress-bar js-progress-bar"></span>
              <h4 class="clean-system__step-title">Receive Fit Finder Kit</h4>
              <div class="clean-system__text">
                Once you’ve placed your order you’ll receive your Fit Finder Kit by mail. Using the kit to match you to your ideal mouthpiece size you’ll then be able to input your size online and the rest of your order will ship.
              </div>
            </div>
            <div class="js-clean-system-step clean-system__step sliced-scroll__item js-scroll-item" title="02">
              <span data-step="2" class="js-clean-system-nav-link clean-system-nav__item">02</span>
              <span class="clean-system__progress js-progress-bar"></span>
              <h4 class="clean-system__step-title">Willo Touches Down</h4>
              <div class="clean-system__text">
                Shortly after you’ve input your mouthpiece size online, you’ll receive your complete Willo Essential Set along with your first Routine Refills shipment containing your perfectly matched mouthpiece and 2 rinse pods.</div>
            </div>
            <div class="js-clean-system-step clean-system__step sliced-scroll__item js-scroll-item" title="03">
              <span data-step="3" class="js-clean-system-nav-link clean-system-nav__item ">03</span>
              <span class="clean-system__progress js-progress-bar"></span>
              <h4 class="clean-system__step-title">Fresh Refills Arrive</h4>
              <div class="clean-system__text">
                3 months into using Willo, and right when you need them, your refills will arrive at your door with a fresh mouthpiece and 2 new rinse pods.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
