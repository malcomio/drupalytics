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
  Drupal.behaviors.code_analyzer = {
    attach: function (context, settings) {
      $(".code-analysis").accordion({
        heightStyle: "content",
        collapsible: true,
        active: false
      });
    }
  }
})(jQuery);