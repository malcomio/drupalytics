<?php

/**
 * @file
 * Contains \Drupal\code_analyzer\AnalyzerInterface.
 */

namespace Drupal\code_analyzer;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Defines a common interface for all code analyzers.
 *
 * @ingroup code_analyzer
 */
interface AnalyzerInterface {
  /**
   * Analyze a single file.
   *
   * @param \Symfony\Component\Finder\SplFileInfo $file
   *   The file that needs to be analyzed.
   *
   * @return array
   *   An array containing the issues that were found for this file. The array
   *   will exist of entities.
   */
  public function file(SplFileInfo $file);

  /**
   * Analyze multiple files.
   *
   * @param array $files
   *   Array containing the files that need be analyzed. The files inside the
   *   array should be \Symfony\Component\Finder\SplFileInfo objects.
   *
   * @return mixed
   *   An array containing the issues that were found for these files. The array
   *   will exist of entities.
   */
  public function files(array $files);

  /**
   * Analyze an entire directory.
   *
   * @param string $directory
   *   The path of the directory that needs to be analyzed.
   *
   * @return array
   *   An array containing the issues that were found for the files in this
   *   directory. The array will exist of entities.
   */
  public function directory($directory);

  /**
   * Checks if a given file should be analayzed.
   *
   * Will check if the file should be analyzed. Some code analyzers come with a
   * public method that allows us to check this automatically. If it's not the
   * case, we will need to do some manual checks. (E.g. ignore pattern, ignored
   * extensions, ...)
   *
   * @param \Symfony\Component\Finder\SplFileInfo $file
   *   The file that needs to be checked.
   *
   * @return bool
   *   True if the file should get analyzed, false if not.
   */
  public function shouldProcess(SplFileInfo $file);

  /**
   * Renders the source code that will be stored.
   *
   * Will render the source code by adding a message to the affected line. We
   * pre-process the code and create a HTML markup of the code and store it as
   * HTML in the database. This will improve the performance when loading the
   * source code again.
   * Never use the full source code of a file, always use the affected code.
   * You can get the affected code via the code parser.
   * @see \Drupal\code_analyzer\CodeParser::getAffectedCode()
   *
   * @param string $source_code
   *   The affected source code. This should not be the entire file, just the
   *   affected lines.
   * @param $highlight_line
   * @param $relative_path
   * @param $message
   *
   * @return mixed
   */
  public function renderSourceCode($source_code, $highlight_line, $relative_path, $message);

  /**
   * Setter for the analyzer.
   *
   * @param object $analyzer
   *   The analyzer that will be used for performing the analysis of the code.
   */
  public function setAnalyzer($analyzer);

  /**
   * Getter for the analyzer.
   *
   * @return object
   *   Returns the analyzer that can be used for analyzing the code.
   */
  public function getAnalyzer();

}