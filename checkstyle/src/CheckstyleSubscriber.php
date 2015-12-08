<?php

namespace Drupal\checkstyle;

use Drupal\checkstyle\Entity\CheckstyleIssue;
use Drupal\code_analyzer\CheckstyleAnalyzer;
use Drupal\code_analyzer\CodeAnalysisEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;

class CheckstyleSubscriber implements EventSubscriberInterface {

  static function getSubscribedEvents() {
    $events['code_analysis.execute'][] = array('onExecute', 0);
    return $events;
  }

  /**
   * @param \Drupal\code_analyzer\CodeAnalysisEvent $event
   *
   * @return array
   *   Array that contains Entities related to the results.
   */
  public function onExecute(CodeAnalysisEvent $event) {
    $analyzer = new CheckstyleAnalyzer();
    krumo($event->getProjectDir());
    $results = $analyzer->analyzeDirectory($event->getProjectDir());
    krumo($results);
    $event->addResults($results);
    krumo($event->getResults());
  }
}