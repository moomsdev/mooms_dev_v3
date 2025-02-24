import "@images/favicon.ico";
import "@styles/theme";
import "airbnb-browser-shims";
import 'popper.js';
import 'bootstrap/dist/js/bootstrap.bundle';
import 'bootstrap/scss/bootstrap.scss';
import "./pages/*.js";
import GSAP from 'gsap';
// import Swup from 'swup';
import Swiper from 'swiper/swiper-bundle.min';

jQuery(document).ready(function () {
  // const swup = new Swup();
  initializePageFeatures();

  swup.hooks.on('content:replace', () => {
    initializePageFeatures();
  });
});

function initializePageFeatures() {
  initSwiperSlider();
  setupHideHeaderOnScroll();
  setupGsap404();
}

/**
 * Initialize swiper slider
 */
function initSwiperSlider() {
  setTimeout(() => {
    new Swiper('.sliders', {
      spaceBetween: 30,
      centeredSlides: true,
      effect: 'fade',
      speed: 1500,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
    });
  }, 500);
}

/**
 * hide/show header when scrolling
 */
function setupHideHeaderOnScroll() {
  let lastScrollTop = 0;
  let header = document.getElementById('header');
  let scrollTimeout;

  window.addEventListener('scroll', function () {
    clearTimeout(scrollTimeout);

    let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScrollTop > lastScrollTop) {
      header.classList.add('hidden');
    } else {
      header.classList.add('hidden');
    }

    lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;

    scrollTimeout = setTimeout(() => {
      header.classList.remove('hidden');
    }, 500);
  });
}

/**
 * GSAP animation for 404 page
 *  https://greensock.com/docs/v3/GSAP
 */
function setupGsap404() {
  GSAP.set("svg", { visibility: "visible" });

  GSAP.to("#spaceman", {
      y: 5, rotation: 2, yoyo: true, repeat: -1, ease: "sine.inOut", duration: 1
  });

  GSAP.to("#starsBig line", {
      rotation: "random(-30,30)", transformOrigin: "50% 50%", yoyo: true, repeat: -1, ease: "sine.inOut"
  });

  GSAP.fromTo("#starsSmall g", { scale: 0 }, { scale: 1, transformOrigin: "50% 50%", yoyo: true, repeat: -1, stagger: 0.1 });

  GSAP.to("#circlesSmall circle", {
      y: -4, yoyo: true, duration: 1, ease: "sine.inOut", repeat: -1
  });

  GSAP.to("#circlesBig circle", {
      y: -2, yoyo: true, duration: 1, ease: "sine.inOut", repeat: -1
  });

  GSAP.set("#glassShine", { x: -68 });
  GSAP.to("#glassShine", {
      x: 80, duration: 2, rotation: -30, ease: "expo.inOut", transformOrigin: "50% 50%", repeat: -1, repeatDelay: 8, delay: 2
  });
}