@if( !isset($_COOKIE['willo_pionner_banner_closed']) )
  <div class="over-header pioneer_modal">
  <div class="over-header__content">
      @include('partials.over-header',['launch_date' => \App\sage('config')->get('theme')['launch_date']])</div>
  <span class="over-header__cross">+</span>
</div>
@endif
<header class="main-header container {{ isset($_COOKIE['willo_pionner_banner_closed']) ? 'no-banner' : '' }}" id="js-header">
  <div class="main-header__content inner">
    <div class="main-header__left">
      <div class="main-header__burger">
        <div class="main-header__burger__line">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <a href="{{site_url()}}" class="main-header__logo"><svg xmlns="http://www.w3.org/2000/svg" class="willo-logo" width="76" height="21" viewBox="0 0 76 21">
          <path  class="main-header__logo--blue" fill="#fff" fill-rule="evenodd" d="M66.781 17.256c-1.839 0-3.1-1.08-3.1-3.186 0-2.563 1.991-4.763 4.396-4.763 1.603 0 3.218.904 3.218 3.316 0 2.21-1.772 4.633-4.514 4.633M7.208 1.274L4.14 13.168c-.69 2.803 1.03 4.001 2.924 4.001 1.838 0 4.131-1.125 4.852-4.01l3.066-11.885h4.034s-2.76 10.634-3.092 11.894c-.698 2.65 1.129 4 2.702 4 2.479 0 4.44-1.506 5.1-4.13l3.025-11.764h3.98L27.54 13.597c-1.2 4.798-5.511 7.103-9.355 7.103-2.202 0-4.21-.792-5.378-2.336-1.835 1.561-4.187 2.336-6.39 2.336-4.067 0-7.446-2.718-6.127-7.865l2.965-11.56h3.953zm40.639 0l-3.655 14.284c-.298 1.117.315 1.78 1.386 1.78h4.635c-.397-.28-.308-1.161-.17-1.703l3.682-14.36h3.761l-3.655 14.283c-.298 1.117.326 1.78 1.331 1.78h5.66c-.555-.74-.852-2.027-.852-3.233 0-4.364 3.594-7.899 8.421-7.899 4.156 0 6.646 2.95 6.646 6.28 0 3.794-2.32 6.641-6.216 7.575-1.018.244-2.516.375-3.793.375H35.096c-2.145 0-4.047-1.131-4-3.72.008-.484.073-.996.224-1.59l2.187-8.57h3.758l-2.304 9.002c-.286 1.065.251 1.78 1.332 1.78h4.282c-.398-.28-.309-1.161-.17-1.703l3.682-14.36h3.76zM36.24 0c1.334 0 2.462 1.058 2.44 2.349-.025 1.265-1.19 2.348-2.524 2.348-1.307 0-2.381-1.083-2.36-2.348C33.822 1.059 34.934 0 36.24 0z"/>
        </svg></a>
      <div class="main-header__menu main-header__menu--desktop">
        @if (has_nav_menu('header'))
          {!! wp_nav_menu($header_menu)!!}
        @endif
      </div>
      <div class="main-header__menu main-header__menu--mobile">
        @if (has_nav_menu('header-mobile'))
          {!! wp_nav_menu($header_menu_mobile)!!}
          <ul>
            <li id="" class="{{is_account_page() ? 'current-menu-item menu-item' : 'menu-item'  }}">
              <a href="{!! get_permalink(wc_get_page_id( 'myaccount' )) !!}">
                {{ is_user_logged_in() ? 'My account' : 'Log in'  }}
              </a>
            </li>
          </ul>
        @endif
      </div>
    </div>
    <div class="main-header__right">
      <a class="main-header__right-btn" href="@php echo get_permalink(\App\sage('config')->get('theme')['fresh_routine_parent_variation']) @endphp">shop</a>
    </div>
  </div>
</header>

