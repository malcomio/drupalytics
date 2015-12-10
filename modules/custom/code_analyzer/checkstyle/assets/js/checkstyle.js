/**
 * @file
 * Javascript behaviors for the checkstyle module.
 */

(function ($) {
  /**
   * Adds the issue message to the source code.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches a behavior that will move a div from a certain place to another.
   */
  Drupal.behaviors.checkstyle = {
    attach: function (context, settings) {
      $('div.geshifilter').each(function() {
        $("div.message", this).appendTo($('ol li.ln-xtra', this));
      });

    }
  }
})(jQuery);
