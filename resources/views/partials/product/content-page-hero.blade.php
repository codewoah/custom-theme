<div class="hero">

  @if ($data["hero_image"])
    <img class="hero__image" src="{{ $data["hero_image"]["url"] }}" alt="Bringing clean to the people." />
  @endif
  <div class="hero__content">
    @if ($data["hero_title"])
      <h1 class="hero__content__title">{!!$data["hero_title"]!!}</h1>
    @endif
    @if ($data["button_hero_label"])
      <a href="{{$data["button_hero_link"]}}" class="hero__content__btn btn btn--blue">{{$data["button_hero_label"]}}</a>
    @endif
  </div>
</div>
