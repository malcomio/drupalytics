<?php

namespace Drupal\code_analyzer\Controller;

use Drupal\code_analyzer\CodeAnalysisEvent;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;

class CodeAnalyzerController extends ControllerBase implements ContainerInjectionInterface {

  public function main() {
    $data = array(
      '#type' => 'markup',
      '#markup' => t('Hello ;-)'),
    );

    $this->launchAnalysis('scheduler');

    return $data;
  }

  public function launchAnalysis($project) {
    /** @var \Drupal\code_analyzer\GitHandler $githandler */
    $githandler = \Drupal::service('code_analyzer.githandler');
    $githandler::retrieveModule($project);

    $dispatcher = \Drupal::service('event_dispatcher');
    /** @var CodeAnalysisEvent $event */
    $event = $dispatcher->dispatch(
      'code_analysis.execute',
      new CodeAnalysisEvent($githandler::getProjectDir($project))
    );
    $results = $event->getResults();

    $data = [
      'title' => $project,
      'type' => 'code_analysis',
    ];
    $entity = Node::create($data);
    $entity->save();

    $issuemapper = \Drupal::service('code_analyzer.issuemapper');
    $issuemapper->mapAnalysisIssues($entity, $results);
  }
}