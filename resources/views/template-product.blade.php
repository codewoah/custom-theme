{{--
  Template Name: Product Template
--}}

@extends('layouts.app')

@section('content')
    <div class="willo_product">
      @if(ProductPage::getLayouts())
      @foreach(ProductPage::getLayouts() as $key => $object)
        @php $data = json_decode(json_encode($object), true)[0];  @endphp

        @if ($data['acf_fc_layout'] == 'hero_section')
          @include('partials.product.content-page-hero', $data)
        @endif

        @if ($data['acf_fc_layout'] == 'features_section')
          @include('partials.section-title', ['mainTitle' => $data["features_section_title"], 'classes' => "bottom-right without-line margin-top-title"])
          <section class="icons inner">
            @if ($data["features_list"])
              @foreach($data["features_list"] as $feature)
                @include('partials.product.content-page-product-icon', ['imageSrc' => $feature['feature_image']['url'] , 'title' => $feature['feature_title'], 'description' => $feature['feature_description']])
              @endforeach
            @endif
          </section>
        @endif

        @if ($data['acf_fc_layout'] == 'pieces_section')
          @php
            $pieces_section_title = get_field('pieces_section_title');
            $pieces_section_title=str_ireplace('<p>','',$pieces_section_title);
            $pieces_section_title=str_ireplace('</p>','',$pieces_section_title);
          @endphp
          @include('partials.section-title', ['mainTitle' => $pieces_section_title, 'classes' => "bottom-left after-text"])
          <p class="title-description inner">{!!$data["pieces_section_description"]!!}</p>
          <section class="pieces-details move-mouthpiece">
            @include('partials.product.content-page-product-piece', $data)

            @if ($data["details_section_title"])
              @include('partials.section-title', ['mainTitle' => $data["details_section_title"], 'classes' => "bottom-left without-line"])
            @endif
            @include('partials.product.content-page-product-details', $data)
          </section>
        @endif

        @if ($data['acf_fc_layout'] == 'the_app_section')
          @include('partials.product.content-page-product-applications', $data)
        @endif

        @if ($data['acf_fc_layout'] == 'qa_section')
          @if ($data["q&a_section_title"])
            @php
              $qa_section_title = get_field('q&a_section_title');
              $qa_section_title=str_ireplace('<p>','',$qa_section_title);
              $qa_section_title=str_ireplace('</p>','',$qa_section_title);
            @endphp
            @include('partials.section-title', ['mainTitle' => $qa_section_title, 'classes' => "top-left before-text margin-top-title"])
          @endif
          @include('partials.product.content-page-product-qa', $data)
        @endif

      @endforeach
      @endif

    </div>

@endsection
