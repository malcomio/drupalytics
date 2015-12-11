<?php

namespace Drupal\checkstyle;

use Drupal\code_analyzer\CodeAnalysisEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
    $analyzer = \Drupal::service('checkstyle.analyzer');
    #krumo($event->getProjectDir());
    $results = $analyzer->directory($event->getProjectDir());
    #krumo($results);
    $event->addResults($results);
    #krumo($event->getResults());
  }
}