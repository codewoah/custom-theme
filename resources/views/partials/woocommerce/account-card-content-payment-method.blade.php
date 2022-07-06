@if( isset($from) && $from === 'order' )
  @php
    $shipping = $order->get_data()['shipping'];
    $token = get_post_meta($order->get_id(), '_stripe_source_id', true);
    $card_info = \App\get_token($token);
  @endphp
@else
  @php
    $shipping = $subscription->get_data()['shipping'];
    $token = get_post_meta($subscription->get_id(), '_stripe_source_id', true);
    $card_info = \App\get_token($token);
  @endphp
@endif
<span>{{ ucfirst(\App\get_card_meta($card_info, 'card_type')) }} card</span>
<div class="flex">
  <span>Ending in {{ \App\get_card_meta($card_info, 'last4') }}</span>
  <span>Expires on {{ \App\get_card_meta($card_info, 'expiry_month') }}/{{ \App\get_card_meta($card_info, 'expiry_year') }}</span>
</div>

<span style="margin-top: 20px">
    {{ $shipping['first_name']  }} {{$shipping['last_name']}}<br/>
    {{ $shipping['address_1']  }} @if($shipping['address_2']) ,{{ $shipping['address_2']  }} @endif<br/>
    {{ $shipping['city']  }}, {{ $shipping['state']  }} {{ $shipping['postcode']  }}<br/>
    {{ $shipping['country']  }}
</span>
