import {AddToCart} from "../util/AjaxEvents";

export default {
  init() {
    set_btn_cart_new_price()
    // JavaScript to be fired on the checkout page
    const form_product = document.querySelector('#willo_product_from');
    let selected_attrs = {'attribute_size': "1", 'attribute_billing-period': "Month"}
    set_variation_cart_id();

    form_product.addEventListener('submit',e =>{
      e.preventDefault();
      const product_vid = form_product.getAttribute('data-product_id');
      AddToCart(product_vid)
    })

    function setQty(size) {
      form_product.setAttribute('data-qty',size.getAttribute('data-qty'))
    }

    document.querySelectorAll('[data-choice]').forEach( size  =>{
      size.addEventListener('click',e=>{
        const choice = size.getAttribute('data-choice')
        selected_attrs = { ...selected_attrs, 'attribute_size': choice }
        const variation_matched = set_variation_cart_id()
        calculate_an_refresh_ui_price(variation_matched)
        document.querySelectorAll('.selector-size__element').forEach( element =>
          element.classList.remove('selector-size__active')
        )

        size.classList.add('selector-size__active')


        if( size.parentElement.classList.contains('select-ui') && size.classList.contains('selector-size__active') ) {
          document.querySelector('.select-ui__options').classList.toggle('show')
          document.querySelector('#select_ui_reveal').classList.toggle('show')
        } else {
          document.querySelector('.select-ui__options').classList.remove('show')
          document.querySelector('#select_ui_reveal').classList.remove('show')
        }


      })
    })

    document.querySelectorAll('input[name="plan_frequency"]').forEach( radio =>{
      radio.addEventListener('change',e => {
        selected_attrs = { ...selected_attrs, 'attribute_billing-period': e.target.value }
        set_variation_cart_id()
        set_btn_cart_new_price()
      })
    })

    function set_variation_cart_id(){
      const attributes = JSON.parse(form_product.getAttribute('data-product_variations'))

      const variation_matched = attributes.filter( attr =>
        attr.attributes.attribute_size === selected_attrs.attribute_size
      )

      if( variation_matched.length > 0 ) {
        const variation_id = variation_matched.filter( attr =>
          attr.attributes['attribute_billing-period'] === selected_attrs['attribute_billing-period']
        )
        form_product.setAttribute('data-product_id',variation_id[0].variation_id)
        document.getElementById('later-subscription').value = variation_id[0].variation_id
      }
      return variation_matched;
    }

    function calculate_yearly_reduction(plans) {
      const plan_month = plans.filter( plan => plan['attributes']['attribute_billing-period'] === "Month" );
      const plan_year = plans.filter( plan => plan['attributes']['attribute_billing-period'] === "Year" );
    }

    function set_btn_cart_new_price() {
      if(document.getElementById('add-cart-price')) {
        let selected_plan = document.querySelector('.willo_product__row__frequency--active strong').textContent;
        const final_price =  parseFloat(willo_frontend.essential_set_price)
        document.getElementById('add-cart-price').textContent = '- $'+final_price
      }
    }

    function calculate_an_refresh_ui_price( plans ) {
      const month_price_selector = document.querySelector('.html_price_Month')
      const year_price_selector = document.querySelector('.html_price_Year')

      plans.forEach( plan =>{
        switch (plan.attributes['attribute_billing-period']) {
          case "Month":
            month_price_selector.innerHTML = plan.price_html;
            break;
          case "Year":
            year_price_selector.innerHTML = plan.price_html;
            break;
        }
      });

      set_btn_cart_new_price()

      calculate_yearly_reduction(plans)
    }


    /**
     * Sticky page shop section while content scrolling
     */
    (function() {
      function getOffset( el ) {
        var _x = 0;
        var _y = 0;
        while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
          _x += el.offsetLeft - el.scrollLeft;
          _y += el.offsetTop - el.scrollTop;
          el = el.offsetParent;
        }
        return { top: _y, left: _x };
      }

      function getInnerHeight( elm ) {
        if( elm ) {
          var computed = getComputedStyle(elm),
            padding = parseInt(computed.paddingTop) + parseInt(computed.paddingBottom);
          return elm.clientHeight - padding
        }

      }

      if (window.innerWidth > 1199) {
        initcleanSystem();
      }

      function initcleanSystem() {
        const $progressBar = document.getElementsByClassName("js-progress-bar");
        let cleanSystem = document.getElementById("clean-system");
        let container = document.getElementById("clean-system-container");
        let subcontainer = document.getElementById("subcontainer");
        let stepBlock = document.getElementsByClassName("js-clean-system-step");
        let navLinks = document.getElementsByClassName("js-clean-system-nav-link");
        let images = document.getElementsByClassName("js-clean-system-image");
        const steps = document.getElementById("steps");

        let offsetElt = getOffset(cleanSystem);
        let cleanSystemPos = offsetElt.top;
        let scrollTop =  window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        let windowBottomPos = scrollTop  + window.innerHeight;
        let bottomEdgeStart = false;
        let currentPosition = null;
        let stepCount = stepBlock.length;
        let scrollStep = 400; // the scrollBar distance needed for change step block
        let evidenceOffsetMargin = null;
        const stepBarHeight = getInnerHeight(steps);

        if(cleanSystem)
          cleanSystem.style.height =  scrollStep * (stepCount + 1) - 111 + 'px';

        let cleanSystemBottomPos = cleanSystemPos + getInnerHeight(cleanSystem);

        if (window.scrollY >= cleanSystemBottomPos && window.innerWidth > 1199) {
          bottomEdgeStart = true;
          currentPosition = cleanSystemPos;
        }

        window.addEventListener("resize", function() {
          cleanSystemPos = offsetElt.top;
          windowBottomPos = window.scrollY + window.innerHeight;
          cleanSystemBottomPos = cleanSystemPos + getInnerHeight(document.getElementById("clean-system"));
          evidenceOffsetMargin = null;
        });

        // Change step on window scroll
        window.addEventListener("scroll", function() {
          if (window.innerWidth < 1200) {
            return;
          }
          windowBottomPos = window.scrollY + window.innerHeight;

          cleanSystemPos = offsetElt.top;
          cleanSystemBottomPos = cleanSystemPos + getInnerHeight(cleanSystem);

          if (window.scrollY >= (cleanSystemPos)) {
            if( container )
              container.classList.add('sticky');

            if(subcontainer)
              subcontainer.classList.remove('hidden');


            if (currentPosition === null) {
              currentPosition = window.scrollY;
            }
            const sectionOffset = window.scrollY - cleanSystemPos;
            const percentageScrolled = +(sectionOffset / (stepBarHeight)).toFixed(2) * 100;

              if (window.scrollY > currentPosition && window.scrollY < (currentPosition * scrollStep)) {
                var status = [];
                for (var z = 0, zen = stepBlock.length; z < zen; z++) {
                  if (percentageScrolled - (stepBlock[z].offsetTop / stepBarHeight).toFixed(2) * 100 >= 0) {
                    status[z] = 1;
                    if (z > 0) {
                      status[z - 1] = 2;
                    }
                   }
                  else {
                    status[z]  = 0;
                  }
                }
                for (var i = 0, len = stepBlock.length; i < len; i++) {
                  if (status[i] > 0) {
                    stepBlock[i].classList.add("clean-system__step--active");
                    navLinks[i].classList.add("clean-system-nav__item--active");
                  } else {
                    stepBlock[i].classList.remove("clean-system__step--active");
                    navLinks[i].classList.remove("clean-system-nav__item--active");
                    $progressBar[i].classList.remove("progress--active");
                  }
                }

                for (var r = 0, re =  $progressBar.length; r < re; r++) {
                  if (status[r] > 0) {
                    $progressBar[r].classList.add("progress--active")
                    $progressBar[r].style.height = Math.min((percentageScrolled - (100 / stepCount) * r) * stepCount, 100) + '%';
                  } else {
                    $progressBar[r].classList.remove("progress--active");
                    $progressBar[r].style.height = '0%';
                  }
                }

                for (var j = 0, le = navLinks.length; j < le; j++) {
                  if (status[j] > 0) {
                    navLinks[j].classList.add("clean-system-nav__item--active");
                  } else {
                    navLinks[j].classList.remove("clean-system-nav__item--active");
                  }
                }

                for (var k = 0, l = images.length; k < l; k++) {
                  if (status[k] == 1){
                    images[k].classList.add("clean-system__image--active");
                  } else {
                    images[k].classList.remove("clean-system__image--active");
                  }
                }
              }

          } else {
            container.classList.remove('sticky');
            subcontainer.classList.add('hidden');
            $progressBar[0].style.height = '0%';
          }

          if (windowBottomPos >= cleanSystemBottomPos) {
            container.classList.add('bottom-position');
            container.classList.remove('sticky');
            subcontainer.classList.add('hidden');
            bottomEdgeStart = true;

            // calcEvidanceOffsetMargin();

          } else if (windowBottomPos <= cleanSystemBottomPos && bottomEdgeStart) {
            container.classList.remove('bottom-position');
            container.classList.add('sticky');
            subcontainer.classList.remove('hidden');
            bottomEdgeStart = false;
          }
        });


        function calcEvidanceOffsetMargin() {

          if (window.innerWidth < 1200) {
            $('.sliced-scroll-pro-o3').css('margin-top', '');
            return;
          }

          if (!bottomEdgeStart || evidenceOffsetMargin) {
            return;
          }

          $('.sliced-scroll-pro-o3').css('margin-top', '');

          let evidenceOffsetTop = Math.floor($('.sliced-scroll-pro-o3').offset().top);
          let verContainerBottomnPos = Math.floor($('.intro-nutrients__container').offset().top + $('.intro-nutrients__container').height());

          evidenceOffsetMargin = Math.floor((evidenceOffsetTop - verContainerBottomnPos));
          $('.sliced-scroll-pro-o3').css('margin-top', -evidenceOffsetMargin + 140);
        }
      }
    }());
  },
  finalize() {
    document.querySelectorAll('.willo-product-detail__gallery__mini__thumb').forEach( thumbnail =>{
      thumbnail.addEventListener('click',()=>{
        document.querySelectorAll('.willo-product-detail__gallery__mini__thumb').forEach( item => item.classList.remove('active') )
        const img = thumbnail.getAttribute('data-big');
        document.querySelector('.willo-product-detail__gallery__full img').setAttribute('src', img)
        document.querySelector('.willo-product-detail__gallery__full img').removeAttribute('srcset')
        thumbnail.classList.add('active')
      })
    })

    document.querySelectorAll('.mobile-product-slider').forEach(slider =>{
      new window.swiper('.mobile-product-slider', {
        spaceBetween: 30,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
      });
    })
  }
};
