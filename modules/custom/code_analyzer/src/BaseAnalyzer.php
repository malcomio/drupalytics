<?php

/**
 * @file
 * Contains \Drupal\code_analyzer\BaseAnalyzer.
 */

namespace Drupal\code_analyzer;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Defines a common analyzer with default methods.
 *
 * @ingroup code_analyzer
 */
class BaseAnalyzer {

  protected $ignore_patterns = array(
    "*/contrib/*",
    "*/*.features.*",
    "*/*.field_group.inc",
    "*/*.layout.*",
    "*/*.pages_default.*",
    "*/*.panels_default.*",
    "*/*strongarm.inc",
    "*/*.views_default.inc",
    "*.yml",
  );

  protected $analyzer;

  /** @var \Drupal\code_analyzer\CodeParser $codeparser */
  protected $codeparser;

  public function __construct(CodeParser $codeparser, $analyzer) {
    $this->codeparser = $codeparser;
    $this->analyzer = $analyzer;
  }

  /**
   * {@inheritdoc}
   */
  public function getAnalyzer() {
    return $this->analyzer;
  }

  /**
   * {@inheritdoc}
   */
  public function setAnalyzer($analyzer) {
    $this->analyzer = $analyzer;
  }

  public function codeparser() {
    return $this->codeparser;
  }

  /**
   * {@inheritdoc}
   */
  public function shouldProcess(SplFileInfo $file) {
    return TRUE;
  }

  public function file(SplFileInfo $file) {
    return array();
  }

  /**
   * {@inheritdoc}
   */
  public function files(array $files) {
    $results = [];
    foreach ($files as $file) {
      if ($this->shouldProcess($file)) {
        $results[] = $this->file($file);
      }
    }
    return $results;
  }

  public function directory($directory) {
    $results = array();
    $finder = new Finder();
    $finder->files()->in($directory);

    foreach ($finder as $file) {
      $issues = $this->file($file);
      if (!empty($issues)) {
        $results = array_merge($results, $issues);
      }
    }
    return $results;
  }
}