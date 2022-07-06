<div class="pieces inner">
  <div class="pieces__left">
    @if ($data["pieces_section_piece_left"])
      @foreach($data["pieces_section_piece_left"] as $piece)
        <div class="pieces__item pieces-item left-item">
          <h3>{{$piece['pieces_section_piece_title']}}</h3>
          <div class="left-item-text">{!! $piece['pieces_section_piece_description'] !!}</div>
        </div>
      @endforeach
    @endif
  </div>
  <div class="pieces__image__left">
    <img src="{{ \App\asset_path('images/sink2@2x.png') }}" alt="willo-pieces" />
  </div>
  <div class="pieces__image">
    <div class="pieces__image__right">
      <img class="first-img" src="{{ \App\asset_path('images/box.png') }}" alt="willo-pieces" />
      <img class="mouthpiece-animation-asset holder above" src="{{ \App\asset_path('images/fix.png') }}" alt="willo-pieces" />
    </div>
  </div>
  <div class="pieces__right">
    @if ($data["pieces_section_piece_right"])
      @foreach($data["pieces_section_piece_right"] as $piece)
        <div class="pieces__item pieces-item ">
          <h3>{{$piece['pieces_section_piece_title']}}</h3>
          <div class="right-item-text">{!! $piece['pieces_section_piece_description'] !!}</div>
        </div>
      @endforeach
    @endif
  </div>
  <div class="pieces__slider">
    <div class="swiper-wrapper">
      @if ($data["pieces_section_piece_left"])
        @foreach($data["pieces_section_piece_left"] as $piece)
          <div class="pieces__item swiper-slide">
            <h3>{{$piece['pieces_section_piece_title']}}</h3>
            <p>{!! $piece['pieces_section_piece_description'] !!}</p>
          </div>
        @endforeach
      @endif
      @if ($data["pieces_section_piece_right"])
        @foreach($data["pieces_section_piece_right"] as $piece)
          <div class="pieces__item swiper-slide">
            <h3>{{$piece['pieces_section_piece_title']}}</h3>
            <p>{!! $piece['pieces_section_piece_description'] !!}</p>
          </div>
        @endforeach
      @endif
    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>
