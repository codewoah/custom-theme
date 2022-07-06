<div data-attribute="billin-period">
  @if(isset($variations_attrs['Billing Period']))
    @foreach( array_reverse($variations_attrs['Billing Period']) as $k => $frequency )
      <div class="willo_product__row__frequency @php echo $frequency === 'Month' ? 'willo_product__row__frequency--active' : '' @endphp">
        <span>
          <input id="frequency-plan-{{ $k }}" @php checked( $frequency, 'Month', true ) @endphp type="radio" value="{{$frequency}}" name="plan_frequency">
          <label for="frequency-plan-{{ $k }}">
            @if( $frequency === 'Month' )
              Every 3 months
            @else
              Annually
              <span class="tag tag--mint">
                save
                <small style="margin-left: 4px;" class="percentage-saved"> 10%</small>
              </span>
            @endif
          </label>
        </span>
        <span class="html_price_{{ $frequency  }}" >
          @php echo \App\template('partials/woocommerce/product_frequency_html_price', ['product' => \App\sage('config')->get('theme')['default_plan'][$frequency]]) @endphp
        </span>
      </div>
    @endforeach
  @endif
</div>

