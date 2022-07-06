<div class="inner">
  <div class="qa">
    @if($data["q&a_section_content"])
      @php
        $i = 0;   
      @endphp
      <div class="qa__accordions">
        @foreach($data["q&a_section_content"] as $qa)
          @if ($i % 2 == 0)
            <div class="qa__accordions__accordion">
              <button class="qa__accordions__accordion__title">{{$qa['pieces_section_q&a_question']}}</button>
              <div class="qa__accordions__accordion__content">
                <p>{{$qa['pieces_section_q&a_response']}}</p>
              </div>
            </div>              
          @endif
          @php
              $i++
          @endphp
        @endforeach
      </div>
      @php
        $i = 0;   
      @endphp
      <div class="qa__accordions">
        @foreach($data["q&a_section_content"] as $qa)
          @if ($i % 2 != 0)
            <div class="qa__accordions__accordion">
              <button class="qa__accordions__accordion__title">{{$qa['pieces_section_q&a_question']}}</button>
              <div class="qa__accordions__accordion__content">
                <p>{{$qa['pieces_section_q&a_response']}}</p>
              </div>
            </div>              
          @endif
          @php
              $i++
          @endphp
        @endforeach
        <div class="qa__accordions__accordion">
          <a href="{{$data["q&a_read_more_button_link"]}}" target="_blank" class="qa__accordions__accordion__more-question">{{$data["q&a_read_more_button_text"]}}</a>
        </div>
      </div>
    @endif
  </div>
  <a href="{{$data['q&a_section_button_link']}}" class="qa--btn btn btn--blue">{{$data['q&a_section_button_text']}}</a>
</div>
