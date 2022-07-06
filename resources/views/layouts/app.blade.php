<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')
  <body @php body_class() @endphp>
    @php do_action('get_header') @endphp
    @include('partials.header')
      <main class="content__main" id="content" role="document">
        @yield('content')
      </main>
    @php do_action('get_footer') @endphp
    @include('partials.footer', [ 'socials_links' => \App\Controllers\OptionPage::getSocialLinksFields() ])
    @php wp_footer() @endphp
    @include('partials/woocommerce/side-cart')
    @include('partials.woocommerce.account.modal-tracking')
  </body>
</html>
