import DomEvents from "../util/DomEvents";
import {
  applyCoupon,
  klaviyoOptinNewsletter,
  klaviyoStoreHowHeard,
  PioneerModal,
  SerchVariation
} from '../util/AjaxEvents'
import {scrollLock} from "../util/ScrollLock";
import {isMobile, validateEmail} from "../util/Helpers";
import {initInterCom} from "../util/InterCom"
import cssVars from 'css-vars-ponyfill';
import Timer from "../classes/Timer";
import Cookies from 'js-cookie';
export default {
  init() {

    /* IE9+ Comptabilty for CSS Vars */
    try {
      cssVars({
        // Options...
        updateURLs: false
      });
    } catch (error) {
      console.log(error);
    }

    // missing forEach on NodeList for IE11
    if (window.NodeList && !NodeList.prototype.forEach) {
      NodeList.prototype.forEach = Array.prototype.forEach;
    }

    document.querySelector('body').addEventListener('click', e => {
      if (e.target.classList.contains('fade_site')) {
        document.querySelector('.side-cart__close').click()
      }
    })


    document.querySelectorAll('.modal__backdrop').forEach(backdrop => {
      backdrop.addEventListener('click', () => {
        document.querySelectorAll('.modal').forEach(modal => {
          modal.classList.remove('visible')
          document.querySelector('body').classList.remove('fade_site')
        });
      })
    });


    document.querySelectorAll('.over-header__cross').forEach(cross => {
      cross.addEventListener('click', e => {
        e.preventDefault()
        cross.closest('.over-header').remove();
        Cookies.set('willo_pionner_banner_closed', '1', {expires: 7});
        document.querySelector('.main-header').classList.add('no-banner')
        document.body.classList.remove('has-banner')
      })
    })

    new Timer()

    if (document.getElementById('submit_how_heard')) {
      document.getElementById('submit_how_heard').addEventListener('click', e => {
        const choice = document.querySelector('.thank-you__where-heard input:checked').value
        if (choice !== '') {
          klaviyoStoreHowHeard(choice, e.target.getAttribute('data-order'))
        } else {
          const other_reason = document.getElementById('other_reason').value
          if (other_reason !== '') {
            klaviyoStoreHowHeard(other_reason, e.target.getAttribute('data-order'))
          }
        }
      })
    }

    window.document.addEventListener('klaviyo_optin_newsletter_success', e => {
      const {from} = e.detail;
      switch (from) {
        case 'sidecart':
          document.querySelectorAll('.side-cart__content form').forEach(form => form.style.display = 'none')
          document.querySelector('.side-cart__content .action').style.display = 'none';

          const title = document.createElement('h6')
          title.textContent = 'Thank you for joining Willo!'
          title.classList.add('thanks-title')

          const text = document.createElement('p')
          text.textContent = ' We will notify you once we are ready for full launch. \n Thank you for being with us on this journey.'
          text.classList.add('thanks-text')

          document.querySelector('.side-cart__content').appendChild(title)
          document.querySelector('.side-cart__content').appendChild(text)

          setTimeout(() => {
            document.querySelectorAll('.side-cart__content form').forEach(form => form.style.display = 'block')
            document.querySelector('.side-cart__content .action').style.display = 'block';

            document.querySelector('.side-cart__content .thanks-text').remove();
            document.querySelector('.side-cart__content .thanks-title').remove();
          }, 5000)
          break;
        case 'form-waitlist':
          if (document.querySelector('.willo-waitlist-optin-form .success')) {
            document.querySelector('.willo-waitlist-optin-form .success').remove()
          }

          const message = document.createElement('span')
          message.classList.add('success')
          message.textContent = 'Thanks! You\'ve successfully joined the waitlist. Check your inbox';
          document.querySelector('.join-waitlist').insertAdjacentElement('afterend', message);
          break;
        case 'footer':
          if (document.querySelector('.main-footer__contact-form .success')) {
            document.querySelector('.main-footer__contact-form .success').remove()
          }

          const footer_message = document.createElement('div')
          footer_message.classList.add('success')
          footer_message.textContent = 'Thanks for subscribing';
          document.querySelector('.main-footer__contact-form').appendChild( footer_message);
          document.querySelector('.main-footer__contact-mail').value = '';
          document.querySelector('.main-footer__contact-btn').setAttribute('disabled',true);
          setTimeout(()=>{
            footer_message.remove();
            document.querySelector('.main-footer__contact-btn').removeAttribute('disabled');
          }, 1500);

          break;
        default:
          break;
      }
    })

    document.querySelectorAll('.main-footer__contact-form').forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();
        const email = form.querySelector('.main-footer__contact-mail').value;
        if (validateEmail(email)) {
          klaviyoOptinNewsletter(email, 'footer')
        }
      })
    })

    document.querySelectorAll('.checkbox').forEach(label => {

      if(label.querySelector('input')) {
        if (label.querySelector('input').checked) {
          label.classList.add('checked')
        }
      }

      label.addEventListener('click', e => {
        e.stopPropagation();
        if(label.querySelector('input')) {
          if (!label.querySelector('input').checked)
            label.classList.add('checked')
          else
            label.classList.remove('checked')
        }

      })
    })

    DomEvents.dynamicEvents()
    // JavaScript to be fired on all pages
    const side_bar_cart = document.querySelector('.side-cart');
    const body = document.querySelector('body')

    window.document.addEventListener('willo-cart-qty-updated', e => {
      const data = e.detail;
      SerchVariation(data.size, data.period)
    })

    window.document.addEventListener('willo-cart-updated', () => {
      body.classList.add('fade_site')
      fetch(willo_frontend.ajax_url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
        body: `action=willo_update_sidecart`
      })
        .then(res => {
          return res.text();
        })
        .then(function (data) {
          side_bar_cart.classList.add('show')
          side_bar_cart.querySelector('.side-cart__content').innerHTML = data
          if( !isMobile.any() ) {
            setTimeout(() => {
              if (side_bar_cart.querySelector('.side-cart__content #enter-pioneer-code input'))
                side_bar_cart.querySelector('.side-cart__content #enter-pioneer-code input').setAttribute('autofocus', 'autofocus');
              if (side_bar_cart.querySelector('.side-cart__content #enter-pioneer-code input'))
                side_bar_cart.querySelector('.side-cart__content #enter-pioneer-code input').focus()
            }, 100)
          }
          DomEvents.dynamicEvents()
          Timer.clear_cart_interval()
          Timer.maybe_display_30_min_cart_to_checkout(
            1800
          )
          scrollLock(
            side_bar_cart
          )

        })
        .catch(function (error) {
          console.log(JSON.stringify(error));
        });

    })

    document.querySelectorAll('.trigger_modal').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const target = link.getAttribute('data-target');
        PioneerModal(target)
      })
    })

    const view_updaters = document.querySelectorAll('[data-updater-view]')
    if (view_updaters.length > 0) {
      view_updaters.forEach(view => {
        view.addEventListener('click', e => {
          e.preventDefault();
          const view_to_show = view.getAttribute('data-updater-view');
          const parent_view = view.closest('.views-wrapper');
          if (parent_view) {
            document.querySelectorAll('.login-form__error').forEach( error => error.remove())
            parent_view.querySelectorAll('.view').forEach(view => {
              view.style.display = 'none';
              parent_view.querySelector('#' + view_to_show).style.display = 'block';
              parent_view.querySelectorAll('.panel-view input').forEach(  input => {
                input.value = '';
                document.querySelectorAll('.login-form__submit button').forEach( btn => btn.style.display = 'none' )
              })
            })
          }
        })
      });
    }

    /**
     * Bouton footer scroll to top
     */
    const scrollToTopButton = document.getElementById('js-top');
    if (scrollToTopButton) {
      const scrollToTop = () => {
        const c = document.documentElement.scrollTop || document.body.scrollTop;
        if (c > 0) {
          window.requestAnimationFrame(scrollToTop);
          window.scrollTo(0, c - c / 10);
        }
      };
      scrollToTopButton.onclick = function (e) {
        e.preventDefault();
        scrollToTop();
      }
    }

    /**
     * Menu Mobile
     */
    const menu = {};
    menu.headerMobile = document.getElementsByClassName('main-header')[0];
    menu.headerMenu = document.getElementsByClassName('main-header__menu--mobile')[0];
    menu.burgerMenu = document.getElementsByClassName('main-header__burger__line')[0];
    menu.homeBurgerLogo = document.getElementsByClassName('main-header__logo--blue')[0];
    menu.homeButton = document.getElementsByClassName('main-header__right-btn')[0];
    menu.pageBody = document.body;

    if (menu.burgerMenu) {
      if ((!(menu.pageBody.classList.contains('home')) && !(menu.pageBody.classList.contains('product')))) {
        menu.homeBurgerLogo.setAttribute("fill", "#142cd2");
        menu.homeButton.style.color = "#142cd2";
      }
      menu.burgerMenu.addEventListener('click', function () {
        menu.burgerMenu.classList.toggle('active');
        menu.headerMenu.classList.toggle('open');
        if((menu.headerMenu.classList.contains('open'))) {
          menu.homeBurgerLogo.setAttribute("fill", "#142cd2");
          menu.homeButton.style.color = "#142cd2";
        }
        else if((menu.headerMobile.classList.contains('js-sticky'))) {
          menu.homeBurgerLogo.setAttribute("fill", "#142cd2");
          menu.homeButton.style.color = "#142cd2";
        }
        else if ((!(menu.pageBody.classList.contains('home')) && !(menu.pageBody.classList.contains('product')))) {
          menu.homeBurgerLogo.setAttribute("fill", "#142cd2");
          menu.homeButton.style.color = "#142cd2";
        }
        else {
          menu.homeBurgerLogo.setAttribute("fill", "#fff");
          menu.homeButton.style.color = "#fff";
        }
      })
    };


    /**
     * Sliders
     */
    window.onload = window.onresize = function () {

      let header = document.getElementById("js-header");
      if(header) {
        sticky()
      }

      /**
       * Slider review
       */
      new window.swiper('.slider__reviews', {
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        pagination: {
          el: '.swiper-pagination',
          type: 'bullets'
        },
        mousewheel: false,
        keyboard: false,
        slidesPerView: 1,
        spaceBetween: 100,
        centredSlides: false,
        autoHeight: true,
        // loop: true,
        breakpoints: {
          1024: {
            slidesPerView: 2,
            spaceBetween: 100
          }
        }
      });

      /**
       * Slider Shop
       */
      if (window.innerWidth < 1199 && document.querySelector('.shop__slider')) {

      var shopSlider = new window.swiper('.shop__slider', {
        effect: 'fade',
        fadeEffect: {
          crossFade: true
        },
        speed: 300,
        pagination: {
          el: '.swiper-pagination-shop',
          type: 'bullets',
          clickable: true,
          renderBullet: function (index, className) {
            function numberAppend(d) {
              return (d < 10) ? `0${d.toString()}` : d.toString();
            }

            return `<span class="${className}"> ${numberAppend(index + 1)} </span>`;
          }
        },
        slidesPerView: 1,
        spaceBetween: 100,
        autoHeight: true
      })
      setTimeout(function () {
        shopSlider.update();
      }, 500);

      // Click action to change slides -> to switch to scroll detection
      window.jQuery('body').on('click', '.shop__slider__pagination', function (e) {
        var x = window.matchMedia("(min-width: 1024px)")
        if (x.matches) {
          const index = Number(jQuery(this).find('.shop__slider__index').data('index') - 1);
          shopSlider.slideTo(index, 350);
          jQuery('.swiper-slide-active').find('.shop__slider__pagination--active ul').removeClass("d-none");
          setTimeout(function () {
            shopSlider.update();
          }, 300);
        }
      });
      window.jQuery('body').on('click', 'a.shop__slider__link', function (e) {
        e.stopPropagation();
        const index = Number(jQuery(this).parents('.shop__slider__pagination').find('.shop__slider__index').data('index') - 1);
        shopSlider.slideTo(index, 350);
        jQuery('.swiper-slide-active').find('.shop__slider__pagination--active ul').toggleClass("d-none");
        setTimeout(function () {
          shopSlider.update();
        }, 300);
      });
    }
  }

    /**
     * Sticky header
     */
    let topOfNav;
    let header = document.getElementById("js-header");
    if(header) {
      topOfNav = header.offsetTop;
      window.addEventListener("scroll", sticky);
    }

    function sticky() {
      let homeBurgerLogo = document.getElementsByClassName('main-header__logo--blue')[0];
      let homeButton = document.getElementsByClassName('main-header__right-btn')[0];
      let headerMenu = document.getElementsByClassName('main-header__menu--mobile')[0];

      if (window.scrollY > topOfNav) {
        header.classList.add("js-sticky");
        homeBurgerLogo.setAttribute("fill", "#142cd2");
        homeButton.style.color = "#142cd2";
      }
      else {
        header.classList.remove("js-sticky");
        if (((document.body.classList.contains('home')) || (document.body.classList.contains('product'))) && !headerMenu.classList.contains('open')) {
          homeBurgerLogo.setAttribute("fill", "#fff");
          homeButton.style.color = "#fff";
        }
      }
    }


    // Fix space bug placeholder contact form 7 select2
    const selectionPlaceholder = document.querySelector('.contact-form .select2-selection__placeholder');
    if(selectionPlaceholder) {
      let selectionPlaceholderText = selectionPlaceholder.innerHTML.replace(/_/g, " ");
      selectionPlaceholderText = selectionPlaceholderText.replace(/#/g, "'");
      selectionPlaceholder.textContent = selectionPlaceholderText;
    }

    /* Contact Form 7’s custom DOM event (mailsent) */
    const contactThankyou = document.querySelector('.contact-thank-you');
    const contactInfo = document.querySelectorAll('.contact-info__info');
    document.addEventListener( 'wpcf7mailsent', function( event ) {
      contactThankyou.classList.remove("d-none");
      contactInfo.forEach(function(contactInfoItem) {
        contactInfoItem.classList.add("d-none");
      });
      setTimeout(() => {
        // Fix space bug placeholder contact form 7 select2
        if(selectionPlaceholder) {
          let selectionPlaceholderText = selectionPlaceholder.innerHTML.replace(/_/g, " ");
          selectionPlaceholderText = selectionPlaceholderText.replace(/#/g, "'");
          selectionPlaceholder.textContent = selectionPlaceholderText;
        }
      }, 10);
    }, false );

    document.addEventListener( 'wpcf7invalid', function( event ) {
      contactThankyou.classList.add("d-none");
      contactInfo.forEach(function(contactInfoItem) {
        contactInfoItem.classList.remove("d-none");
      });
      // Fix space bug placeholder contact form 7 select2
      if(selectionPlaceholder) {
        let selectionPlaceholderText = selectionPlaceholder.innerHTML.replace(/_/g, " ");
        selectionPlaceholderText = selectionPlaceholderText.replace(/#/g, "'");
        selectionPlaceholder.textContent = selectionPlaceholderText;
      }
    }, false );

    document.addEventListener( 'wpcf7mailfailed', function( event ) {
      contactThankyou.classList.add("d-none");
      contactInfo.forEach(function(contactInfoItem) {
        contactInfoItem.classList.remove("d-none");
      });
      // Fix space bug placeholder contact form 7 select2
      if(selectionPlaceholder) {
        let selectionPlaceholderText = selectionPlaceholder.innerHTML.replace(/_/g, " ");
        selectionPlaceholderText = selectionPlaceholderText.replace(/#/g, "'");
        selectionPlaceholder.textContent = selectionPlaceholderText;
      }
    }, false );

    /**
     * Ajoute la classe 'current-link' sur le lien de la page ou nous sommes.
     */
      function highlightCurrent() {
        const curPage = document.URL;
        const links = document.getElementsByTagName('a');
        for (let link of links) {
          if (link.href == curPage) {
            link.classList.add("current-link");
          }
        }
      }
      try {
        highlightCurrent();
      } catch (error) {
        console.log(error);
      }

      window.jQuery('body').on('click', '#showInterCom',function(e){
        Intercom('showNewMessage');
      });

      /* Init InterCom */
      initInterCom();

  },
  finalize() {
    window.jQuery('body').on('click', '.action__enter-discout img',function(e){
      window.jQuery(this).next().toggle()
      if( window.jQuery(document).find('.order-sep').hasClass('coupon') ) {
        window.jQuery(document).find('.order-sep').removeClass('coupon')
      }
      window.jQuery(this).parent().toggleClass('open')
      window.jQuery(this).parent().find('.error').remove()
      window.jQuery(this).parent().find('input').val('')
      window.jQuery(this).next().find('.validate').on('click', e =>{
        e.preventDefault();
        e.stopImmediatePropagation();
        applyCoupon(
          window.jQuery(this).next().find('input').val()
        )
      })
    });

    window.jQuery('body').on('click', '#coupon-code',function(e){
      window.jQuery('body').find('.action__enter-discout img').trigger('click');
      if( window.jQuery(document).find('.order-sep').hasClass('coupon') ) {
        window.jQuery(document).find('.order-sep').removeClass('coupon')
      }
    });

  },
};
