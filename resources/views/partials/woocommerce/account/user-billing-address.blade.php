@php
  $customer_id = get_current_user_id();
  /** @var WC_Customer $customer */
  $customer = new WC_Customer( $customer_id )
@endphp

<span>
    {{ $customer->get_billing_first_name() }} {{$customer->get_billing_last_name()}}<br/>
    {{ $customer->get_billing_address_1()  }} @if($customer->get_billing_address_2()) ,{{ $customer->get_billing_address_2()  }} @endif<br/>
    {{ $customer->get_billing_city()  }}, {{ $customer->get_billing_state()  }} {{ $customer->get_billing_postcode()  }}<br/>
    {{ $customer->get_billing_country()  }}
</span>

<div class="flex flex--a-center" style="margin-top: 20px">
<span>
    @php echo get_user_meta( get_current_user_id() ,'shipping_phone',true) @endphp
</span>
  <a href="{!!  esc_url( wc_get_endpoint_url( 'edit-address-billing', '' , wc_get_page_permalink( 'myaccount' ) ) ) !!}">edit</a>
</div>
