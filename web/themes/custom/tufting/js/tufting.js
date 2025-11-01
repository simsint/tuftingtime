
(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.sdactSection = {
    attach: function (context, settings) {

      /*WebFont.load({ google: { families: ["Inter:regular,italic"] } });*/

      !function (o, c) { var n = c.documentElement, t = " w-mod-"; n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch") }(window, document);

      $(window).scroll(function () {
        if ($(window).scrollTop() >= 1) {
          $('.header').addClass('is-stuck');
          $('.hero').addClass('top');
        }
        else {
          $('.header').removeClass('is-stuck');
          $('.hero').removeClass('top');
        }
      });

      $('.disclamer__close').click(function () {
        $('.disclamer').hide();
      });

      $('.discover__banner-list').slick({
        autoplay: true,
        autoplaySpeed: 0, // Set to 0 for continuous scroll without pause
        speed: 5000, // Adjust this value to control the speed of the continuous scroll (in milliseconds)
        cssEase: 'linear', // Ensures a smooth, consistent animation
        slidesToShow: 2, // Number of slides visible at once
        slidesToScroll: 1, // Number of slides to scroll at a time
        infinite: true, // Essential for continuous looping
        arrows: false, // Hide navigation arrows if desired
        dots: false, // Hide pagination dots if desired
        variableWidth: true,
        // Add other desired options here, e.g., responsive settings
      });

      $('.drug-slider__wrapper').slick({
        autoplay: true,
        autoplaySpeed: 0, // Set to 0 for continuous scroll without pause
        speed: 5000, // Adjust this value to control the speed of the continuous scroll (in milliseconds)
        cssEase: 'linear', // Ensures a smooth, consistent animation
        slidesToShow: 2, // Number of slides visible at once
        slidesToScroll: 1, // Number of slides to scroll at a time
        infinite: true, // Essential for continuous looping
        arrows: false, // Hide navigation arrows if desired
        dots: false, // Hide pagination dots if desired
        variableWidth: true,
        // Add other desired options here, e.g., responsive settings
      });

    }
  };

})(jQuery, Drupal, drupalSettings);
