(function ($, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.sdactBodySection = {
    attach: function (context, settings) {

      function accordion() {
        if ($(this).attr('id') !== "displayed") {
          $('.faq__item').removeAttr('id');
          $('.faq__item').find('#closed').css('display', 'initial');
          $('.faq__details').slideUp(1000);

          $(this).attr('id', 'displayed');
          $(this).children().last().slideDown(1000);

          var closed = $(this).find('#closed');
          closed.css('display', 'none');
          $(this).children().first().children().last().attr('class', 'faq__icon');
        } else {
          $(this).removeAttr('id');
          $(this).children().last().slideUp(1000);
          var closed = $(this).find('#closed');
          closed.css('display', 'initial');
        }
      }

      $('.faq__item').on('click', accordion);

    }
  };

})(jQuery, Drupal, drupalSettings);
