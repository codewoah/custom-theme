{{--
  Template Name: Willo account
--}}
@extends('layouts.app')
@section('content')
  <div class="spacer-top spacer-top--account">
  @while(have_posts()) @php the_post() @endphp
    @if(is_user_logged_in())
      <div class="inner">
        <h2 class="my-account-title">Hey, @php echo get_user_meta( get_current_user_id(),'first_name', true); @endphp</h2>
      </div>
    @else
        @include('partials.section-title', ['mainTitle' => get_the_title(), 'classes' => "bottom-left"])
    @endif
    @include('partials.content-page')
  @endwhile
  </div>
@endsection
