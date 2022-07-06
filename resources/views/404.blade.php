@extends('layouts.app')

@section('content')
  <div class="inner">
    <div class="page-content">
      @include('partials.page-header')
      <a style="margin: 0 auto 24px;    display: table;" href="{!! home_url() !!}" class="btn btn--blue">Go to home</a>
    </div>
  </div>
@endsection
