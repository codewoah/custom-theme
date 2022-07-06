<div data-attribute="size">
  <div class="selector-size">
    @php
      $plans = \App\sage('config')->get('theme')['plans'];
      $select_ui_plan = array_filter( $plans, function($plan){
        return $plan['select_ui'];
      });
      $non_select_ui_plan = array_filter( $plans, function($plan){
        return !$plan['select_ui'];
      });
      @endphp
      @foreach($non_select_ui_plan as $name => $value)
      <span class="selector-size__element @php echo $name === 'solo' ? 'selector-size__active' : '' @endphp" data-choice="{{ $value['plan']  }}" data-qty="{{ $value['qty']  }}">
        <h3>{{ $name  }}</h3>
        <div class="pictos">
          @if($value['plan'] === '1')
            <img src="@php echo \App\asset_path('images/solo-plan-icon.svg') @endphp" alt="">
          @else
            <img src="@php echo \App\asset_path('images/duo-icon-1.svg') @endphp" alt="">
            <img src="@php echo \App\asset_path('images/duo-icon-2.svg') @endphp" alt="">
          @endif
        </div>
      </span>
      @endforeach
      <div class="select-ui">
        <span class="select-ui__selected selector-size__element" data-choice="3">
          <h3>
            <small>{{ array_key_first($select_ui_plan)  }}</small>
            <span id="select_ui_reveal"></span>
          </h3>
          <div class="pictos">
            <img src="@php echo \App\asset_path('images/family-icon-1.svg') @endphp" alt="">
            <img src="@php echo \App\asset_path('images/family-icon-2.svg') @endphp" alt="">
            <img src="@php echo \App\asset_path('images/family-icon-3.svg') @endphp" alt="">

          </div>
        </span>
        <div class="select-ui__options">
          @foreach($select_ui_plan as $name => $ui_select_plan)
            <span data-value="{{ $ui_select_plan['plan'] }}" class="select-ui__options__option">
              For {{ $ui_select_plan['plan'] }} people
            </span>
          @endforeach
        </div>
      </div>
  </div>
</div>


