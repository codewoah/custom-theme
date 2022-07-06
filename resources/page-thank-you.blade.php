{{--
  Template Name: Than you
--}}
@extends('layouts.checkout')
@section('content')
    <div class="thank-you inner">
        <div class="thank-you__logo">
            <a href="{!! home_url('/') !!}">
            <img src="{!! \App\asset_path('images/willo-logo-blue.svg') !!}" alt="">
            </a>
        </div>
        <h3>Thank you Olivia for your order!</h3>
        <p>Good things are coming your way. <br>You should receive an order confirmation email shortly. </p>
        <div class="thank-you__where-heard">
            <h3>We’re curious, how did you heard <br>about Willo?</h3>
            <div class="options">
                <div class="options__item">
                    <input id="friend" type="radio" name="reason" value="From a friend or relative" checked>
                    <label for="friend">From a friend or relative</label>
                </div>
                <div class="options__item">
                    <input id="dental_event" type="radio" value="At a dental event" name="reason">
                    <label for="dental_event">At a dental event</label>
                </div>
                <div class="options__item">
                    <input id="social_media" value="From social media (Instagram, FB, Twitter, etc)" type="radio" name="reason">
                    <label for="social_media">From social media (Instagram, FB, Twitter, etc) </label>
                </div>
                <div class="options__item">
                    <input id="web_search" value="By web search" type="radio" name="reason">
                    <label for="web_search">By web search</label>
                </div>
                <div class="options__item">
                    <input id="other" value="" type="radio" name="reason">
                    <label for="other">
                        Another way
                        <input type="text" placeholder="write here">
                    </label>
                </div>
            </div>
            <button type="button" class="btn btn--blue">Submit</button>
        </div>
        <img class="mouth" src="{!! \App\asset_path('images/illus-pink-mouth.svg') !!}" alt="">
        <img class="wave" src="{!! \App\asset_path('images/wave-vector@2x.png') !!}" alt="">
    </div>
@endsection
