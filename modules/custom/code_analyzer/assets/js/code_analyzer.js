/**
 * @file
 * Javascript behaviors for the Code Analyzer module.
 */

(function ($) {
  /**
   * Adds an accordion UI effect to the list of issues.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches accordion behavior to code analysis issues.
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
