{{--
  Template Name: Pros Template
--}}

@extends('layouts.app')

@section('content')
    <div class="willo_contact willo_pros">
      @include('partials.section-title', ['mainTitle' => ContactPage::title(), 'classes' => "bottom-left"])
      <div class="title-description inner">{!!ContactPage::titleDescription()!!}</div>
      @if(ProsPage::getLayouts())
      @foreach(ProsPage::getLayouts() as $key => $object)
        @php $data = json_decode(json_encode($object), true)[0];  @endphp
          @if ($data['acf_fc_layout'] == 'splited_section')
            <div class="block-texts inner">
              @if ($data['block_texts']['left_section_content'] === "text")
                <div class="block-texts__right-text block-texts__right-text--bold-blue">{!!$data['block_texts']['left_section_text']!!}</div>
              @endif
              @if ($data['block_texts']['left_section_content'] === "image")
                <div class="block-texts__right-text block-texts__right-text--image"><img src="{{ $data["block_texts"]['left_section_image']["url"] }}" alt="team-member"> </div>
              @endif
              @if ($data['block_texts']['right_section_content'] === "text")
                <div class="block-texts__left-text">{!!$data['block_texts']['right_section_text']!!}</div>
              @endif
            </div>
          @endif

          @if ($data['acf_fc_layout'] == 'form_section')
            <section class="contact-section">
              <div class="contact-section inner">
                <div class="contact-form">
                  @if ($data['contact_form'][0])
                    <h2>{{$data['contact_form'][0]['post_title']}}</h2>
                    @php
                      echo do_shortcode('[contact-form-7 id="'.$data["contact_form"][0]['ID'].'" title="'.$data["contact_form"][0]['post_title'].'"]')
                    @endphp
                  @endif
                </div>
                
                <div class="contact-info">
                  @if ($data)
                    @foreach($data['contact_info_section'] as $contact_info)
                      <div class="contact-info__info contact-info__info--pros">
                        <h3>{{$contact_info['contact_info_title']}}</h3>
                        {!!$contact_info['contact_info_description']!!}
                      </div>
                    @endforeach
                  @endif

                  <div class="contact-thank-you d-none">
                    <div class="contact-thank-you__info">
                      <h3>Thank You</h3>
                      <img class="hero__image" src="{{ \App\asset_path('images/thank-you.svg') }}" alt="thank-you" />
                      {!!$data['thank_you_message']!!}
                    </div>
                  </div>
                </div>
              </div>
            </section>
          @endif

          @if ($data['acf_fc_layout'] == 'pros_team_section')
            <div class="pros-team">
              <div class="pros-team inner">
                <div class="pros-team__members">
                  @foreach($data['pros_team'] as $team_member)
                    <div class="pros-team__member">
                      <div class="pros-team__member__image">
                        <img src="{{$team_member['team_member']['member_image']['url']}}" alt="team-member">
                      </div>
                      <div class="pros-team__member__info">
                        <h3>{{$team_member['team_member']['member_name']}}</h3>
                        <h5>{{$team_member['team_member']['member_post']}}</h5>
                        <p>{{$team_member['team_member']['member_description']}}</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          @endif

          @if ($data['acf_fc_layout'] == 'exhibitions_section')
            @include('partials.section-title', ['mainTitle' => $data['exhibitions_section']["exhibitions_section_title"], 'classes' => "bottom-right"])
            <div class="title-description inner">{!!$data['exhibitions_section']["exhibitions_section_subtitle"]!!}</div>
            <div class="exhibitions-section__images">
              <div class="exhibitions-section__images__image">
                <img src="{{ $data['exhibitions_section']["exhibitions_images"][0]["exhibition_image"]["url"] }}" alt="exhibitions" />
              </div>
              <div class="exhibitions-section__images__image">
                <img src="{{ $data['exhibitions_section']["exhibitions_images"][1]["exhibition_image"]["url"] }}" alt="exhibitions" />
              </div>
              <div class="exhibitions-section__images__image exhibitions-section__images__image--two">
                <img src="{{ $data['exhibitions_section']["exhibitions_images"][2]["exhibition_image"]["url"] }}" alt="exhibitions" />
                <img src="{{ $data['exhibitions_section']["exhibitions_images"][3]["exhibition_image"]["url"] }}" alt="exhibitions" />
              </div>
            </div>
          @endif
          @if ($data['acf_fc_layout'] == 'three_lines_title_section')
            <div class="third-title">
              <h4>{{$data['three_lines_title']['head_title']}}</h4>
              <h2 class="third-title__first-text">{{$data['three_lines_title']['title_line_1']}}</h2> 
              <h2 class="third-title__second-text">{{$data['three_lines_title']['title_line_2']}}</h2> 
              <h2 class="third-title__third-text">{{$data['three_lines_title']['title_line_3']}}</h2>
            </div>
          @endif
        @endforeach
      @endif
  </div>
@endsection
