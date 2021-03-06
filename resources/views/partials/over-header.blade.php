@if(time() < $launch_date && \App\is_in_stock(App\sage('config')->get('theme')['side_product_add_cart']['essential_set']) )
  <div class="over-header__desc" data-end="{{$launch_date}}">
    Exclusive availability for our top 1000 Pioneer members for <span id="24h_timer"></span>
  </div>
  @if(!isset($_COOKIE['willo_pioneer_program_ok']))
    <div class="over-header__desc over-header__links">
      <a data-target="pioneer-code" class="over-header__link trigger_modal" href=""><strong>Enter code</strong></a> or
      <a data-target="pioneer-waitlist" class="over-header__link trigger_modal" href=""><strong>Join the waitlist</strong></a>
    </div>
  @endif
@elseif( !\App\is_in_stock(App\sage('config')->get('theme')['side_product_add_cart']['essential_set']) )
  <div class="over-header__desc">
    Willo is temporarily sold out – <a data-target="pioneer-waitlist" class="over-header__link trigger_modal" href=""><strong>Join the waitlist</strong></a> to be notified when it’s available!
  </div>
@else
  <div class="over-header__desc">
    Last chance for <a class="over-header__link" href="https://medium.com/@letswillo/willo-day-1-d633ceb2fd53" target="_blank" rel="noreferrer noopener"><strong>Willo Pioneers</strong></a>! l 100% Smiles Guaranteed・30 Day Free Returns・Pause, Swap or Stop Anytime
  </div>
@endif
