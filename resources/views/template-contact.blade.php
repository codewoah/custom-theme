{{--
  Template Name: Contact Template
--}}

@extends('layouts.app')

@section('content')
    <div class="willo_contact">
      @include('partials.section-title', ['mainTitle' => ContactPage::title(), 'classes' => "bottom-left"])
      <div class="title-description inner">{!!ContactPage::titleDescription()!!}</div>
      <section class="contact-section">
        <div class="contact-section inner">
          <div class="contact-form">
            @if (ContactPage::contactFormField()["contactFormShortcode"])
              <h2>{{ContactPage::contactFormField()["contactFormTitle"]}}</h2>
              @php
                echo do_shortcode(ContactPage::contactFormField()["contactFormShortcode"])
              @endphp
            @endif
          </div>
          
          <div class="contact-info">
            @if (OptionPage::getContactFields())
              @foreach(OptionPage::getContactFields() as $contact_info)
                <div class="contact-info__info">
                  <h3>{{$contact_info['contact_info_title']}}</h3>
                  {!!$contact_info['contact_info_description']!!}
                </div>
              @endforeach
            @endif
            
            <div class="contact-thank-you d-none">
              <div class="contact-thank-you__info">
                <h3>Thank You</h3>
                <img class="hero__image" src="{{ \App\asset_path('images/thank-you.svg') }}" alt="thank-you" />
                {!!get_field('thank_you_message', 'option')!!}
              </div>
          </div>
        </div>
      </section>
  </div>
@endsection
