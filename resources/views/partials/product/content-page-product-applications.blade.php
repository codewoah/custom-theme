<section class="applications">
  <div class="applications--content inner">
    <div class="applications__images">
      <img class="applications__images__image vertical-image" src="{{ \App\asset_path('images/vertical-title@2x.png') }}" alt="the-app" />
      @if ($data["the_app_image_1"])
        <img class="applications__images__image" src="{{ $data["the_app_image_1"]["url"] }}" alt="app-willo-1" />
      @endif
      @if ($data["the_app_image_2"])
        <img class="applications__images__image applications__images__image--bottom-app" src="{{ $data["the_app_image_2"]["url"] }}" alt="app-willo-2" />
      @endif
    </div>
    <div class="applications__content">
      <img class="vertical-image" src="{{ \App\asset_path('images/vertical-title@2x.png') }}" alt="the-app" />
      @if ($data["the_app_title"])
        <h2>{!!$data["the_app_title"]!!}</h2>
      @endif
      @if ($data["the_app_description"])
        {!!$data["the_app_description"]!!}
      @endif
      <div class="applications__content__btns">
        <a href="{{$data["the_app_ios_link"]}}" class="{{ $data["the_app_is_ready"] ? "" : "disabled" }} applications__content__btns__btn applications__content__btns__btn--ios btn btn--transparence"><img src="{{ \App\asset_path('images/icon-apple.svg') }}" alt="icon-apple" /> <p>IOS</p> </a>
        <a href="{{$data["the_app_android_link"]}}" class="{{ $data["the_app_is_ready"] ? "" : "disabled" }} applications__content__btns__btn applications__content__btns__btn--android btn btn--transparence"><img src="{{ \App\asset_path('images/icon-android.svg') }}" alt="icon-android" /> <p>ANDROID</p> </a>
      </div>
      @if (!$data["the_app_is_ready"])
        <p class="stay-tuned-apps">Will be available soon, stay tuned!</p>
      @endif
    </div>
  </div>
</section>
