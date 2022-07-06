@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    <div class="inner">
      <div class="page-content">
        @include('partials.page-header')
        @include('partials.content-page')
      </div>
    </div>
  @endwhile
@endsection
