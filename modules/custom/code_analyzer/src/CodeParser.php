<?php
/**
 * Created by PhpStorm.
 * User: legovaer
 * Date: 06/12/15
 * Time: 11:21
 */

namespace Drupal\code_analyzer;


class CodeParser {
  
  protected $file;

  protected $display_lines;
  
  public function __construct() {
    $this->display_lines = \Drupal::config('checkstyle.settings')->get('display_lines');
  }

  public function setFile($file) {
    $this->file = $file;
  }
  
  public function getFile() {
    return $this->file;
  }
  
  public function getAffectedCode($affectedLine) {
    $start_line = $this->getStartLine($affectedLine);
    $end_line = $this->getEndLine($affectedLine);

    $lines = file($this->file->getRealPath());
    $source_code = "";
    for ($i = ($start_line - 1); $i <= $end_line; $i++) {
      $source_code .= $lines[$i];
    }

    return $source_code;
  }

  public function getStartLine($affectedLine, $display_lines = NULL) {
    $display_lines = isset($display_lines) ? $display_lines : $this->display_lines;
    return $affectedLine < $display_lines ? 1 : $affectedLine - $display_lines;
  }

  public function getHighlightLine($affectedLine, $display_lines = NULL) {
    $display_lines = isset($display_lines) ? $display_lines : $this->display_lines;
    return $affectedLine < $display_lines ? $affectedLine : $display_lines + 1;
  }

  public function getEndLine($affectedLine, $display_lines = NULL) {
    $display_lines = isset($display_lines) ? $display_lines : $this->display_lines;
    $highlight_line = $this->getHighlightLine($affectedLine);
    $start_line = $this->getStartLine($affectedLine);
    return $affectedLine < $display_lines ? $highlight_line + $display_lines : $start_line + 2 * $display_lines;
  }
  
}