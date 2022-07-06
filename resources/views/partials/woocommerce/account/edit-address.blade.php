@php
  $customer_id = get_current_user_id();
  /** @var WC_Customer $customer */
  $customer = new WC_Customer( $customer_id );
  $woo_countries = new WC_Countries();
  $states = $woo_countries->get_states('US');
  $state_saved =  $type === 'billing' ? $customer->get_billing_state() :  $customer->get_shipping_state();
@endphp

<form action="#" class="form-edit-address adress-form-submitter" id="edit-customer-{{ $type  }}-address">
  <div class="form-edit-address__row-wide">
    <input class="required" type="text" name="{{ $type  }}_first_name_field" id="{{ $type  }}_first_name_field" value="{!! $type === 'billing' ? $customer->get_billing_first_name() :  $customer->get_shipping_first_name()  !!}">
    <input class="required" type="text" name="{{ $type  }}_last_name_field" id="{{ $type  }}_last_name_field" value="{!! $type === 'billing' ? $customer->get_billing_last_name() :  $customer->get_shipping_last_name()  !!}">
  </div>
  <div class="form-edit-address__row-full">
    <input type="text" class="required" name="{{ $type  }}_address_1_field"  id="{{ $type  }}_address_1_field" value="{!! $type === 'billing' ? $customer->get_billing_address_1() :  $customer->get_shipping_address_1()  !!}">
  </div>
  <div class="form-edit-address__row-full">
    <input type="text" name="{{ $type  }}_address_2_field" id="{{ $type  }}_address_2_field" value="{!! $type === 'billing' ? $customer->get_billing_address_2() :  $customer->get_shipping_address_2()  !!}">
  </div>
  <div class="form-edit-address__row-full">
    <input class="required" type="text" name="{{ $type  }}_country_field" id="{{ $type  }}_country_field" value="{!! $type === 'billing' ? $customer->get_billing_city() :  $customer->get_shipping_city()  !!}">
  </div>
  <div class="form-edit-address__row-wide">
    <select class="required select2" name="{{ $type  }}_state_field" id="{{ $type  }}_state_field">
      @foreach($states as $value => $state)
        <option @php selected($state_saved, $value, true) @endphp value="{{$value}}">{{$state}}</option>
      @endforeach
    </select>
   <input class="required" type="text" name="{{ $type  }}_postcode_field" id="{{ $type  }}_postcode_field" value="{!! $type === 'billing' ? $customer->get_billing_postcode() :  $customer->get_shipping_postcode()  !!}">
  </div>
  @if( $type === 'billing')
  <div class="form-edit-address__row-full">
    <input class="required" type="text"  name="{{ $type  }}_phone_field" id="{{ $type  }}_phone_field" value="{!! $customer->get_billing_phone() !!}">
  </div>
  @endif
  <button type="submit" class="btn btn--blue">Save</button>
</form>
