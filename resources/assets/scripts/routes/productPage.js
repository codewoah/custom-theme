import ScrollMagic from "scrollmagic";
import { TimelineMax, CSSPlugin, TweenLite, TweenMax, gsap} from 'gsap';
import { ScrollMagicPluginGsap } from "scrollmagic-plugin-gsap";
ScrollMagicPluginGsap(ScrollMagic, TweenMax, TimelineMax);

export default {
  init() {
    // JavaScript to be fired on the product page
    var acc = document.getElementsByClassName("qa__accordions__accordion__title");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = `${panel.scrollHeight}px`;
        }
      });
    }

    window.onload = function () {
      new window.swiper('.pieces__slider', {
        // Optional parameters
        spaceBetween: 40,
        autoHeight: true,
        loop: true,
        mousewheel: false,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
          type: 'bullets'
        },
      })
    };

      TweenLite.defaultOverwrite = false;

      var controller = new ScrollMagic.Controller();

      var desktopGrip = new TimelineMax()
        .to(".mouthpiece-animation-asset.holder", 1, {top: 0}, 1)

      function loop(elements, callback)
      {
        for (let i = 0; i < elements.length; i++) {
          callback(elements[i]);
        }
      }

      var s = document.querySelectorAll(".pieces-item:first-child, .left-item");
      var scene = new ScrollMagic.Scene({
        triggerElement: "section.pieces-details",
        triggerHook: "onLeave",
        offset: 200,
        duration: 0,
      }).setTween(desktopGrip.fromTo(s, 0.3, {autoAlpha: 0}, {autoAlpha: 1})).addTo(controller);
      loop(document.querySelectorAll('.pieces-item:not(:first-child)'), elem => {
        desktopGrip.fromTo(elem, 0.6, {autoAlpha: 0}, {autoAlpha: 1});
        var scene2 = new ScrollMagic.Scene({
          triggerElement: elem,
          triggerHook: 2,
          duration: 100,
          reverse: false
        }).setTween(desktopGrip).addTo(controller);
      });

      var scene3 = new ScrollMagic.Scene({
        triggerElement: "section.pieces-details",
        duration: 500,
        triggerHook: "onLeave"
      }).setPin("section.pieces-details", {pushFollowers: true}).setClassToggle("section.pieces-details", "active").addTo(controller);
      var scene4 = new ScrollMagic.Scene({
        triggerElement: ".move-mouthpiece",
        duration: 1500,
        offset: -700,
        triggerHook: "onLeave"
      }).setTween(desktopGrip).addTo(controller);
  },
};

