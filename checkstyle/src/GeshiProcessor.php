<?php

/**
 * @file
 * Contains \Drupal\checkstyle\CheckstyleGeshiProcesser.
 */

namespace Drupal\checkstyle;

use Drupal\geshifilter\GeshiFilterProcess;
use Drupal\Component\Utility\Html;

/**
 * Defines a GeSHi processor for code related to checkstyle issues.
 *
 * @package Drupal\checkstyle
 */
class GeshiProcessor extends GeshiFilterProcess {

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
   * @param int $line_numbering
   *   The line numbering mode, one of LINE_NUMBERS_* from GeshiFilter class.
   * @param int $linenumbers_start
   *   The line number to start from.
   * @param bool $inline_mode
   *   When to write all styles inline or from a css.
   * @param string $title
   *   The title to use in code.
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

  public static function overrideGeshiDefaults(\Geshi &$geshi, $langcode) {
    $config = \Drupal::config('geshifilter.settings');
    parent::overrideGeshiDefaults($geshi, $langcode);
    $geshi->enable_classes(TRUE);
    $geshi->set_header_type((int) $config->get('code_container', GESHI_HEADER_PRE));
  }

}