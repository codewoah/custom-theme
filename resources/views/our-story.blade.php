{{--
  Template Name: Our Story
--}}
@extends('layouts.app')
@section('content')
  @if(OurStoryPage::getLayouts())
    <div class="willo-our-story">
      @foreach(OurStoryPage::getLayouts() as $key => $object)
        @php $data = json_decode(json_encode($object), true)[0];  @endphp

        @if ($data['acf_fc_layout'] == 'text_intro')
          <div class="willo-our-story__how inner">
            @if($data['first_big_title'])
              @php
                $big_title =$data['first_big_title'];
                $big_title=str_ireplace('<p>','',$big_title);
                $big_title=str_ireplace('</p>','',$big_title);
              @endphp
              @include('partials.section-title', ['mainTitle' => $big_title, 'classes' => "bottom-right text-right ie"])
            @endif
            <div class="our-story__how__content">
              <div class="our-story__how__content__title">{!! $data['first_title'] !!}</div>
              <div class="our-story__how__content__text">{!! $data['first_text'] !!}</div>
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'values')
          <div class="willo-our-story__values inner">
            <div class="our-story__values__content">
              @if($data['first_values'])
                @foreach($data['first_values'] as  $first_value)
                  {!! $first_value['first_value'] !!}
                @endforeach
              @endif
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'photo_text')
          <div class="willo-our-story__photos inner">
            <div class="our-story__photos__content">
              <div class="our-story__photos__img--one">
                <img src="{!! $data['second_image_one']['sizes']['medium'] !!}" srcset="{!! $data['second_image_one']['sizes']['medium'] !!}" alt="" />
              </div>
              <div class="our-story__photos__text">{!! $data['second_text'] !!}</div>
            </div>
            <div class="our-story__photos__img--two">
              <img alt="" src="{!! $data['second_image_two']['sizes']['medium'] !!}" srcset="{!! $data['second_image_two']['sizes']['medium'] !!}"/>
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'photo_text_left')
          <div class="willo-our-story__what inner">
            <div class="our-story__what__content">
              <div class="our-story__what__title">{!! $data['third_title'] !!}</div>
              <div class="our-story__what__text">{!! $data['third_text'] !!}</div>
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'members_picture')
          <div class="willo-our-story__members">
            @if($data['fourth_title'])
              @php
                $member_title =$data['fourth_title'];
                $member_title=str_ireplace('<p>','',$member_title);
                $member_title=str_ireplace('</p>','',$member_title);
              @endphp
              @include('partials.section-title', ['mainTitle' => $member_title, 'classes' => "bottom-left"])
            @endif
            <div class="our-story__members__gallery">
              @if($data['member_picture'])
                @foreach($data['member_picture'] as $member)
                  @if($member['choices'] == 'image')
                    <div class="our-story__member">
                      <img class="our-story__member__img" src="{!! $member['image'] !!}" srcset="{!! $member['image'] !!}" alt=""/>
                    </div>
                  @else
                    <div class="our-story__member"></div>
                  @endif
                @endforeach
              @endif
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'list_logos')
          <div class="willo-our-story__members__footer">
            <div class="members__footer__title">{!! $data['fifth_title'] !!}</div>
            <div class="members__footer__logos">
              @if($data['fifth_logos'])
                @foreach($data['fifth_logos'] as $clientlogo)
                  <div class="members__footer__logo">
                    <img src="{!! $clientlogo['fifth_logo'] !!}" srcset="{!! $clientlogo['fifth_logo'] !!}" alt="" />
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'list_investors')
          <div class="willo-our-story__investors inner">
            <div class="our-story__investors__title">{!! $data['sixth_title'] !!}</div>
            <div class="our-story__investors__desc">{!! $data['sixth_desc'] !!}</div>
            <div class="our-story__investors__logos">
              @if($data['sixth_logos'])
                @foreach($data['sixth_logos'] as $investorlogo)
                  <div class="investors__logos__item">
                    <img src="{!! $investorlogo['sixth_logo'] !!}" srcset="{!! $investorlogo['sixth_logo'] !!}" alt="" />
                  </div>
                @endforeach
              @endif
            </div>
          </div>
        @endif

        @if ($data['acf_fc_layout'] == 'center_title')
          <div class="willo-our-story__join">
            <div class="our-story__join__img">
              <svg class="our-story__join__img--desk" xmlns="http://www.w3.org/2000/svg" width="1440" height="594" viewBox="0 0 1440 594">
                <path fill="#FFF0E4" fill-rule="evenodd" d="M-516-35.366c0 135.077 457.643-122.469 683.651 141.235 0 0 121.964 195.705 326.319 50.087 0 0 244.095-184.74 492.769 29.215 0 0 211.107 225.7 481.935 128.102v279.425L-516 593.615v-628.98z"/>
              </svg>
              <svg class="our-story__join__img--mob" xmlns="http://www.w3.org/2000/svg" width="414" height="430" viewBox="0 0 414 430">
                <path fill="#FFF0E4" fill-rule="evenodd" d="M-331-50.573c0 69.839 236.869-63.32 353.848 73.023 0 0 63.126 101.186 168.897 25.897 0 0 126.34-95.517 255.05 15.105 0 0 109.266 116.695 249.442 66.233v299.307L-331 429.466v-480.04z"/>
              </svg>
            </div>
            <div class="our-story__join__content">
              <div class="our-story__join__title">{!! $data['seventh_title'] !!}</div>
              <div class="our-story__join__text">{!! $data['seventh_text'] !!}</div>
            </div>
          </div>
        @endif

      @endforeach
    </div>
  @endif
@endsection
