<?php

/**
 * @file
 * Contains \Drupal\checkstyle\Analyzer.
 */

namespace Drupal\checkstyle;

use Drupal\checkstyle\Entity\CheckstyleIssue;
use Drupal\code_analyzer\AnalyzerInterface;
use Drupal\code_analyzer\BaseAnalyzer;
use Drupal\code_analyzer\CodeParser;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Defines an analyzer for checkstyle issues.
 *
 * @ingroup code_analyzer
 */
class Analyzer extends BaseAnalyzer implements AnalyzerInterface {

  /** @var  GeshiProcessor $geshi_processor */
  protected $geshi_processor;

  /**
   * Constructs an Analyzer object.
   *
   * @param \Drupal\code_analyzer\CodeParser $codeparser
   *   The code parser that will be used for analyzing files.
   * @param \Drupal\checkstyle\GeshiProcessor $geshi_processor
   *   The processor that will transform the source code to HTML.
   */
  public function __construct(CodeParser $codeparser, GeshiProcessor $geshi_processor) {
    parent::__construct($codeparser, new \PHP_CodeSniffer());
    $this->configureAnalyzer();
  }

  /**
   * Adjust configuration for PHPCS.
   */
  private function configureAnalyzer() {
    $this->analyzer->cli->setCommandLineValues(array('--report=checkstyle'));
    $this->analyzer->initStandard("vendor/drupal/coder/coder_sniffer/Drupal");
    $this->analyzer->setIgnorePatterns($this->ignore_patterns);
    $this->analyzer->setAllowedFileExtensions(array('php', 'inc', 'module'));
  }

  /**
   * {@inheritdoc}
   */
  public function shouldProcess(SplFileInfo $file) {
    return $this->analyzer->shouldProcessFile($file->getRealPath(), $file->getBasename());
  }

  /**
   * {@inheritdoc}
   */
  public function file(SplFileInfo $file) {
    $issues = array();

    if ($this->shouldProcess($file)) {
      $fileReport = $this->analyzer->reporting->prepareFileReport($this->analyzer->processFile($file->getRealPath()));
      /** @var SplFileInfo $file */
      $this->codeparser->setFile($file);

      foreach ($fileReport['messages'] as $line => $lineErrors) {
        foreach ($lineErrors as $column => $colErrors) {
          foreach ($colErrors as $error) {
            $code = $this->renderSourceCode(
              $this->codeparser->getAffectedCode($line),
              $line,
              $file->getRelativePathname(),
              $error['message']
            );
            $data = [
              "line" => $line,
              "code" => $code,
              "message" => $error['message'],
              "relative_path" => $file->getRelativePathname(),
              "display_lines" => \Drupal::config('checkstyle.settings')->get('display_lines'),
              "issue_type" => $error['source'],
            ];
            $issue = array_merge($data, $error);
            $entity = CheckstyleIssue::create($issue);
            $entity->save();
            $issues[] = $entity;
          }
        }
      }
      return $issues;
    }
  }

  /**
   * @{@inheritdoc}
   */
  public function renderSourceCode($source_code, $highlight_line, $relative_path, $message) {
    $highlightLine = $this->codeparser()
      ->getHighlightLine(
        $highlight_line,
        \Drupal::config('checkstyle.settings')->get('display_lines')
      );
    $geshi = $this->geshi_processor->getGeshi($source_code, 'php');
    $geshi->highlight_lines_extra($highlightLine);
    $title = $relative_path . ', line ' . $highlight_line;

    return $this->geshi_processor->geshiProcessCustomGeshi($source_code, 'php', $highlight_line, $message, $title, $geshi);
  }

}
