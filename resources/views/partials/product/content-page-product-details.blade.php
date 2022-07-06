<div class="details inner">
  <div class="details__table">
    @if($data["details_section_detail"])
      @foreach($data["details_section_detail"] as $detail)
        <div class="details__table__item">
          <p class="details__table__item__type">{{$detail['pieces_section_details_title']}}</p>
          <p class="details__table__item__value">{{$detail['pieces_section_details_description']}}</p>
        </div>
        <hr />
      @endforeach
    @endif
  </div>
  @if($data["details_section_image"])
    <div class="details__image">
      <img src="{{ $data["details_section_image"]["url"] }}" alt="willo-details" />
    </div>
  @endif
</div>
