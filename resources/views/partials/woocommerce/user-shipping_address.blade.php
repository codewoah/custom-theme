<span>
    {{ $shipping['first_name']  }} {{$shipping['last_name']}}<br/>
    {{ $shipping['address_1']  }} @if($shipping['address_2']) ,{{ $shipping['address_2']  }} @endif<br/>
    {{ $shipping['city']  }}, {{ $shipping['state']  }} {{ $shipping['postcode']  }}<br/>
    {{ $shipping['country']  }}
</span>

<span style="margin-top: 20px">
    @php echo get_user_meta( get_current_user_id() ,'shipping_phone',true) @endphp
</span>
