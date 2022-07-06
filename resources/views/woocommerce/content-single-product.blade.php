@php
  global $product;
  /** @var WC_Product_Subscription_Variation $essential_set */
  $essential_set = wc_get_product(
      \App\sage('config')->get('theme')['side_product_add_cart']['essential_set']
  )
@endphp
<div class="willo-product-detail {{ !\App\is_in_stock(App\sage('config')->get('theme')['side_product_add_cart']['essential_set']) ? 'sold-out' : ''  }}">
  @include('partials.woocommerce.product.mobile-slider')
  <div class="inner flex">
    @include('partials.woocommerce.product.product_gallery')
    @include('partials.woocommerce.product.plan_chooser')
  </div>
  @if (OptionPage::getIconsFields())
    <section class="icons_section">
      @if (OptionPage::getIconsFields()["icons_section_title"])
        @include('partials.section-title', ['mainTitle' => OptionPage::getIconsFields()["icons_section_title"], 'classes' => "bottom-right"])
      @endif
      @if (OptionPage::getIconsFields()["icons_section_subtitle"])
        <p>{{OptionPage::getIconsFields()["icons_section_subtitle"]}}</p>
      @endif
      <div class="icons col-3-1">
        @if (OptionPage::getIconsFields()["icons_list"])
          @foreach(OptionPage::getIconsFields()["icons_list"] as $icon)
            @include('partials.product.content-page-product-icon', ['imageSrc' => $icon['icon_image']['url'], 'title' => $icon['icon_title'], 'description' => $icon['icon_description']])
          @endforeach
        @endif
      </div>
    </section>
  @endif
  @include('partials.product.content-page-shop-slider')
  @include('partials.slider')
</div>
</div>
