<!doctype html>
<html {!! get_language_attributes() !!}>
@include('partials.head')
<body @php body_class() @endphp>
@php do_action('get_header') @endphp
@include('partials.woocommerce.checkout.order-review-mobile')
<div class="container" role="document">
    <main class="container__main">
      @yield('content')
    </main>
</div>
@if( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) )
  @php do_action('get_footer') @endphp
  @include('partials.footer')
@endif
@php wp_footer() @endphp
</body>
</html>
