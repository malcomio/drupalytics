<?php

/**
 * @file
 * Contains \Drupal\node\Controller\NodeController.
 */

namespace Drupal\checkstyle\Controller;

use Drupal\checkstyle\CheckstyleGeshiProcessor;
use Drupal\checkstyle\Entity\CheckstyleIssue;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

/**
 * Returns responses for Node routes.
 */
class CheckstyleController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays add content links for available content types.
   *
   * Redirects to node/add/[type] if only one content type is available.
   *
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   *   A render array for a list of the node types that can be added; however,
   *   if there is only one node type defined for the site, the function
   *   will return a RedirectResponse to the node add page for that one node
   *   type.
   */
  public function result() {
    /*$build = array();
    $high = 3;
    $med = 5;
    $low = 123;
    $total = $high + $med + $low;
    $rows = array();
    $header = array(
      $this->t('Total'),
      $this->t('High Priority'),
      $this->t('Medium Priority'),
      $this->t('Low Priority')
    );
    $row = [
      ['data' => ['#markup' => $total]],
      ['data' => ['#markup' => $high]],
      ['data' => ['#markup' => $med]],
      ['data' => ['#markup' => $low]],
    ];
    $rows[] = $row;
    $build['summary'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
      '#caption' => $this->t('Summary')
    ];


    /** RESULTS */

    /*$results = $this->loadAnalysis("/Users/legovaer/Dropbox/Work/GitHub/drupal-code-grader/src/checkstyle-warnings.xml");
    foreach ($results as $category => $types) {
      foreach ($types as $typeName => $type) {
        //var_dump($issue);
        $data = [
          [
            '#type' => 'details',
            '#title' => $type['message'] . " (" . count($type["issues"]) . ")",
            '#theme_wrappers' => array('details'),
          ],
/*            "data" => [
              '#type' => 'markup',
              '#prefix' => '<em>',
              '#suffix' => '</em>',
              '#markup' => $this->t("This file ends with no newline character. It won't render properly on a terminal, and it's considered a bad practice. Add a simple line feed as the last character to fix it.")
          ]*//*
        ];
        $build[strtolower($category)] = $data;
      }
    }
/*    $build['resilts'] = array(
      '#type' => 'details',
      '#title' => $this->t('Text files should end with a newline character'),
      '#theme_wrappers' => array('details'),
    );
    $build['resilts']['edata'] = [
      '#type' => 'markup',
      '#prefix' => '<em>',
      '#suffix' => '</em>',
      '#markup' => $this->t("This file ends with no newline character. It won't render properly on a terminal, and it's considered a bad practice. Add a simple line feed as the last character to fix it.")
    ];*/
    //echo var_dump($this->loadAnalysis("/Users/legovaer/Dropbox/Work/GitHub/drupal-code-grader/src/checkstyle-warnings.xml"));

    /**
     * Display results
     */
   //* $results = $this->analyzeProject("scheduler");
    /*$results = $this->sortResultsByType($results);
    krumo($results);

    $data = array(
      '#type' => 'markup',
      '#markup' => 'Hello!',
    );*/

    /**
     * Add a CheckstyleIssue
     */
    $edit = array(
      'severity' => "1",
      'code' => '<html></html>',
      'fixable' => TRUE,
      'priority' => "WARNING",
      'source' => 'Drupal.blabla',
      'relative_path' => 'src/Entity/Form/CheckStyleIssueForm.php',
      'line' => 13,
      'issue_type' => 'Drupal.blabla.ietmas',
      'field_source' => "test",
      'field_message' => "test2",
      'entity_type' => 'checkstyle_issue',
    );


    $entity = CheckstyleIssue::create($edit);
    $entity->save();
    //krumo($entity);*/

    /**
     * Get entity_id by title
     */
    /*$result = db_select('node_revision__issue_type', 'it')
      ->fields('it', array('entity_id'))
      ->condition('it.issue_type_value', 'Drupal.blabla.ietms')
      ->orderBy('it.revision_id', 'DESC')
      ->range(0, 1)
      ->execute();

    krumo($result->fetchField());*/

    $data = array(
      '#type' => 'markup',
      '#markup' => 'test',
    );
    return $data;

  }

  private function sortResultsByType($results) {
    $sorted_array = [];

    foreach ($results as $result) {
      $sorted_array[$result['source']][] = $result;
    }

    return $sorted_array;
  }

  private function renderIssue(array $issue) {
    return $this->displaySourceCode(
      $issue['source'],
      $issue['type'],
      $this->getStartLine($issue['line']),
      $issue['message'],
      t('@path, line @line', array('@path' => $issue['relative_path'], '@line', $issue['line']))
    );
  }



  private function displaySourceCode($source_code, $lang, $start_line, $highlight_line, $message = '', $title = '') {
    $geshi_processor = new CheckstyleGeshiProcessor();
    $geshi = $geshi_processor::getGeshi($source_code, $lang);
    $geshi->highlight_lines_extra(array($highlight_line));
    $txt = $geshi_processor->geshiProcess("<html></html>", $lang, $start_line, $message, $title, $geshi);
    $build = array(
      '#type' => 'markup',
      '#markup' => $txt,
      '#attached' => array(
        'library' => array(
          'checkstyle/checkstyle',
        )
      )
    );
    return $build;
  }

}
