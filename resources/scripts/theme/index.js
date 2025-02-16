import "@images/favicon.ico";
import "@styles/theme";
import "airbnb-browser-shims";
import "./pages/*.js";
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
}

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
