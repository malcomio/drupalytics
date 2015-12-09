<?php
/**
 * Created by PhpStorm.
 * User: legovaer
 * Date: 06/12/15
 * Time: 11:03
 */

namespace Drupal\code_analyzer;

use Symfony\Component\EventDispatcher\Event;

class CodeAnalysisEvent extends Event {

  protected $results;
  protected $project_dir;

  public function __construct($project_dir) {
    $this->project_dir = $project_dir;
  }

  public function getProjectDir() {
    return $this->project_dir;
  }

  public function setProjectDir($project_dir) {
    $this->project_dir = $project_dir;
  }

  public function getResults() {
    return $this->results;
  }

  public function addResults(array $results) {
    $this->results = empty($this->results) ? $results : array_merge($this->results, $results);
  }

}