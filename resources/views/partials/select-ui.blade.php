<div class="input-select-ui">
  <span class="input-select-ui__selected">
    @if($value)
      {{$value}}
    @else
      <span>{{ $label }}</span>
    @endif
  </span>
  <div class="input-select-ui__options">
    @foreach($options as $option)
      <div class="input-select-ui__options__option">{{$option}}</div>
    @endforeach
  </div>
</div>
