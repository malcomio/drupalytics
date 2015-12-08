/**
 * @file
 * Javascript behaviors for the Book module.
 */

(function ($) {
  /**
   * Adds summaries to the book outline form.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches summary behavior to book outline forms.
   */
  Drupal.behaviors.checkstyle = {
    attach: function (context, settings) {
      $('div.geshifilter').each(function() {
        $("div.message", this).appendTo($('ol li.ln-xtra', this));
      });

    }
  }
})(jQuery);