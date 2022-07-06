{{--
  Template Name: Home Template
--}}
@extends('layouts.app')
@section('content')
  @if(FrontPage::getLayouts())
    @foreach(FrontPage::getLayouts() as $key => $object)
      @php $data = json_decode(json_encode($object), true)[0];  @endphp

      @if ($data['acf_fc_layout'] == 'home_hero')
        <div class="content__main__hero">
          <img class="hero-desktop" src="{!! $data["home_hero_image_dsk"] !!}" srcset="{!! $data["home_hero_image_dsk"] !!}" alt=""/>
          <img class="hero-mobile" src="{!! $data['home_hero_image_mob'] !!}" srcset="{!! $data['home_hero_image_mob'] !!}" alt=""/>
          <div class="content__main__hero__block container">
            <div class="content__main__hero__content inner">
              <h1 class="t-h1 content__main__hero__title">{!! $data['home_hero_title'] !!}</h1>
              <img class="launch_video launch_video--mobile" src="{!! \App\asset_path('images/icon-play.svg') !!}" alt="">
              <p class="content__main__hero__desc">{!! $data['home_hero_text'] !!}</p>
              <div class="content__main__hero__btn">
                <a class="btn btn--blue" href="{!! $data['home_hero_btn_link'] !!}">{!! $data['home_hero_btn_text'] !!}</a>
                <a  class="btn btn--salmon launch_video launch_video--desktop" href="#">
                  <span>
                    <img src="{!! \App\asset_path('images/triangle.svg') !!}" alt="">
                    PLAY VIDEO
                  </span>
                </a>
              </div>
            </div>
          </div>
        </div>
      @endif

      @if ($data['acf_fc_layout'] == 'home_presentation')
        <div class="content__main__clean">
          <svg class="content__main__clean__wave--dsk" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="1440" height="626" viewBox="0 0 1440 626">
            <path fill="#FFF0E4" fill-rule="evenodd" d="M-270.326-14.02c0 118.367 400.993-107.319 599.025 123.764 0 0 106.867 171.496 285.925 43.89 0 0 213.88-161.886 431.772 25.601 0 0 184.975 197.781 422.278 112.256v333.705l-1739 .804V-14.02z"/>
          </svg>
          <svg class="content__main__clean__wave--mobile" xmlns="http://www.w3.org/2000/svg" width="414" height="720" viewBox="0 0 414 620">
            <path fill="#FFF0E4" fill-rule="evenodd" d="M-324-15.027c0 93.941 326.744-85.174 488.107 98.224 0 0 87.079 136.108 232.982 34.834 0 0 174.277-128.48 351.823 20.318 0 0 150.724 156.969 344.088 89.091v414.922L-324 643V-15.027z"/>
          </svg>
          <img class="content__main__clean__img" src="{!!$data['first_image_mask'] !!}" srcset="{!!$data['first_image_mask'] !!}" alt=""/>
          <div class="content__main__clean__circle">
            <div class="circle__title">
              {!!$data['first_circle_title']  !!}
            </div>
            {!!$data['first_circle_text'] !!}
          </div>
        </div>
      @endif

      @if ($data['acf_fc_layout'] == 'home_services')
        <div class="content__main__services">
          @if($data['second_services_title'])
            @php
              $services_title =$data['second_services_title'];
              $services_title=str_ireplace('<p>','',$services_title);
              $services_title=str_ireplace('</p>','',$services_title);
            @endphp
            @include('partials.section-title', ['mainTitle' => $services_title, 'classes' => "bottom-right after-text margin-top-title"])
          @endif
          <div class="services__items inner">
            @if ($data["second_services_items"])
              @foreach($data["second_services_items"] as $service)
                <div class="services__item">
                  <div class="services__item__img">
                    <img src="{!! $service['services_item_img'] !!}" srcset="{!! $service['services_item_img'] !!}" alt=""/>
                   </div>
                  <span class="services__item__title">{!! $service['services_item_title'] !!}</span>
                  <div class="services__item__text">{!! $service['services_item_text'] !!}</div>
                </div>
              @endforeach
            @endif
          </div>
          <div class="services__btn inner">
            <a class="btn btn--blue" href="{!!$data['second_services_btn_link'] !!}">{!!$data['second_services_btn_text'] !!}</a>
          </div>
        </div>
      @endif

      @if ($data['acf_fc_layout'] == 'home_video')
        <div class="content__main__video">
          @if($data['third_video_title'])
            @php
              $video_title =$data['third_video_title'];
              $video_title=str_ireplace('<p>','',$video_title);
              $video_title=str_ireplace('</p>','',$video_title);
            @endphp
            @include('partials.section-title', ['mainTitle' => $video_title, 'classes' => "bottom-left before-text"])
          @endif
          <div class="video__elems">
            <img class="video__elems__tiles" src="{{ \App\asset_path('images/tiles.jpg') }}" srcset="{{ \App\asset_path('images/tiles@2x.jpg'), \App\asset_path('images/tiles@3x.jpg') }}" alt="" />
            <div class="video__elems__rect"></div>
            <div class="video__elems__block container">
              <div class="video__elems__content inner">
                <div class="video__elems__video" id="itemsWrapper">
                  <div class="video__elems__cover" id="cover">
                    <div class="cover cover-wrapper">
                      <div class="video__title">{!!$data['third_video_subtitle'] !!}</div>
                      <div class="video__text">{!!$data['third_video_text'] !!}</div>
                      <div class="cover__play" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50">
                          <g fill="none" fill-rule="evenodd">
                            <circle cx="25" cy="25" r="25" fill="#142CD2"/>
                            <path fill="#FFF" stroke="#142CD2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.714" d="M28.268 17.179L39.357 32.704 17.179 32.704z" transform="rotate(90 28.268 24.941)"/>
                          </g>
                        </svg>
                        <span>{!!$data['third_video_btn_text'] !!}</span>
                      </div>
                    </div>
                  </div>
                  <div id="over" class="overlay">
                    <button class="fullview__close" id="close" aria-label="Close preview"><svg aria-hidden="true" width="24" height="22px" viewBox="0 0 24 22"><path d="M11 9.586L20.192.393l1.415 1.415L12.414 11l9.193 9.192-1.415 1.415L11 12.414l-9.192 9.193-1.415-1.415L9.586 11 .393 1.808 1.808.393 11 9.586z" /></svg></button>
                    <div class="overlay-content">
                        <div id="player"></div>
                    </div>
                  </div>
                </div>
                <div class="video__elems__gif">
                  <video autoplay loop muted playsinline>
                    <source src="{!! $data['third_video_gif'] !!}" type="video/mp4" />
                  </video>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif

{{--      @if ($data['acf_fc_layout'] == 'home_skills')
        <div class="content__main__values inner">
          <div class="values__items">
            @if($data['fourth_skills'])
              @foreach($data['fourth_skills'] as $skill)
                <div class="values__item">
                  <div class="values__item__number">{!! $skill['fourth_skill_number'] !!}</div>
                  <div class="values__item__desc">
                    {!! $skill['fourth_skill_text'] !!}
                  </div>
                </div>
              @endforeach
            @endif
          </div>
          <div class="values__text">{!!$data['fourth_skills_warning'] !!}</div>
        </div>
      @endif--}}

      @if ($data['acf_fc_layout'] == 'home_recycle')
        @if($data['fourth_recycle_title_section'])
          @php
            $recycle_title =$data['fourth_recycle_title_section'];
            $recycle_title=str_ireplace('<p>','',$recycle_title);
            $recycle_title=str_ireplace('</p>','',$recycle_title);
          @endphp
          @include('partials.section-title', ['mainTitle' => $recycle_title, 'classes' => "bottom-right margin-top-title"])
        @endif
        <div class="content__main__recycle">
          <div class="recycle inner">
            <div class="recycle__content">
              <div class="recycle__img">
                <img src="{!!$data['fourth_recycle_image'] !!}" srcset="{!!$data['fourth_recycle_image'] !!}" alt="" />
              </div>
              <div class="recycle__logo">
                <img src="{!!$data['fourth_recycle_logo'] !!}" srcset="{!!$data['fourth_recycle_logo'] !!}" alt="" />
              </div>
              <div class="recycle__text">
                <div class="recycle__text__title">{!!$data['fourth_recycle_title'] !!}</div>
                <div class="recycle__text__text">{!!$data['fourth_recycle_text'] !!}</div>
              </div>
            </div>
          </div>
        </div>
      @endif

      @if ($data['acf_fc_layout'] == 'home_slider')
          @include('partials.slider')
      @endif
      @if ($data['acf_fc_layout'] == 'home_logos')
        <div class="content__main__slider">
          @if ($data['hide_logos'] == false)
          <div class="slider-partners">
            @if($data['fifth_logos'])
              @foreach($data['fifth_logos'] as $logo)
                <div class="slider-partners__item">
                  <a target="_blank" rel="noreferrer noopener" href="{!! $logo['logo_link'] !!}"><img src="{!! $logo['fifth_logo'] !!}" srcset="{!! $logo['fifth_logo'] !!}" alt="" /></a>
                </div>
              @endforeach
            @endif
          </div>
          @endif
        </div>
      @endif

      @if ($data['acf_fc_layout'] == 'home_join')
        <div class="content__main__join">
          <div class="join inner">
            <div class="join__title t-h2">{!!$data['sixth_join_title'] !!}</div>
            <div class="join__content">
              <div class="join__img">
                <img src="{!!$data['sixth_join_img'] !!}" srcset="{!!$data['sixth_join_img'] !!}" alt="" />
              </div>
              <div class="join__text">
                <ul class="join__text__items">
                  @if($data['sixth_join_reasons'])
                    @foreach($data['sixth_join_reasons'] as $reason)
                      <li class="join__text__item">{!! $reason['sixth_join_reason'] !!}</li>
                    @endforeach
                  @endif
                </ul>
              </div>
              <div class="join__btn">
                <a class="btn btn--blue" href="{!! $data['sixth_join_btn_text_link'] !!}">{!! $data['sixth_join_btn_text'] !!}</a>
              </div>
            </div>
          </div>
        </div>
      @endif

    @endforeach
  @endif
@endsection



