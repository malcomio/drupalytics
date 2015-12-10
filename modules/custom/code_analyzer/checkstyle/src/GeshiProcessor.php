<?php

/**
 * @file
 * Contains \Drupal\checkstyle\GeshiProcessor.
 */

namespace Drupal\checkstyle;

use Drupal\geshifilter\GeshiFilterProcess;
use Drupal\Component\Utility\Html;

/**
 * Defines a GeSHi processor for code related to checkstyle issues.
 *
 * @ingroup checkstyle
 */
class GeshiProcessor extends GeshiFilterProcess {

  /**
   * Get the GeSHi object.
   *
   * @param string $source_code
   *   The source code that will need to be displayed via GeSHi.
   * @param string $lang
   *   The language of the source code.
   *
   * @return \GeSHi|string
   *   The GeSHi object, if the library has not been loaded it will just
   *   return the $source_code.
   */
  public static function getGeshi($source_code, $lang = "PHP") {
    $source_code = trim($source_code, "\n\r");

    // Load GeSHi library (if not already).
    $geshi_library = libraries_load('geshi');
    if (!$geshi_library['loaded']) {
      drupal_set_message($geshi_library['error message'], 'error');
      return $source_code;
    }

    // Create GeSHi object.
    return self::geshiFactory($source_code, $lang);
  }

  /**
   * Set the GeSHi object.
   *
   * @param \GeSHi $geshi
   */
  public function setGeshiClass(\GeSHi $geshi) {
    $this->geshi = $geshi;
  }

  /**
   * Geshifilter wrapper for GeSHi processing.
   *
   * @param string $source_code
   *   Source code to process.
   * @param string $lang
   *   Language from sourcecode.
   * @param int $linenumbers_start
   *   The line number to start from.
   * @param string $message
   *   The message that will be displayed in the highlighted section.
   * @param string $title
   *   The title to use.
   * @param \GeSHi $geshi
   *   (optional) The GeSHi object that will be used for generating the HTML.
   *
   * @return string
   *   The sourcecode after process by Geshi.
   */
  public static function geshiProcessCustomGeshi($source_code, $lang, $linenumbers_start = 1, $message = '', $title, $geshi = NULL) {

    $source_code = trim($source_code, "\n\r");

    if (!is_object($geshi)) {
      // Load GeSHi library (if not already).
      $geshi_library = libraries_load('geshi');
      if (!$geshi_library['loaded']) {
        drupal_set_message($geshi_library['error message'], 'error');
        return $source_code;
      }

      // Create GeSHi object.
      $geshi = self::geshiFactory($source_code, $lang);
    }
    self::overrideGeshiDefaults($geshi, $lang);
    // Some more GeSHi settings and parsing.
    // Block source code mode.
    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
    $geshi->start_line_numbers_at($linenumbers_start);
    $message = '<div class="message"><p>' . Html::Escape($message) . '</p></div>';
    $source_code = '';
    if (isset($title)) {
      $source_code .= '<div class="geshifilter-title">' . Html::Escape($title) . '</div>';
    }
    $source_code .= '<div class="geshifilter">' . $message . $geshi->parse_code() . '</div>';

    return $source_code;
  }

  /**
   * {@inheritdoc}
   */
  public static function overrideGeshiDefaults(\Geshi &$geshi, $langcode) {
    $config = \Drupal::config('geshifilter.settings');
    parent::overrideGeshiDefaults($geshi, $langcode);
    $geshi->enable_classes(TRUE);
    $geshi->set_header_type((int) $config->get('code_container', GESHI_HEADER_PRE));
  }

}
