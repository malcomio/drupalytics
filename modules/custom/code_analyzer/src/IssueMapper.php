<?php
/**
 * Created by PhpStorm.
 * User: legovaer
 * Date: 06/12/15
 * Time: 12:02
 */

namespace Drupal\code_analyzer;


use Drupal\checkstyle\Entity\CheckstyleIssue;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;

class IssueMapper {

  protected $connection;

  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function mapAnalysisIssues(Node $entity, array $results) {
    $nid = $entity->id();
    foreach ($results as $issue_entity) {
      /** @var EntityInterface $issue_entity */
        $this->connection->insert('code_analyzer_project_issues')
          ->fields(array(
            'nid' => $nid,
            'inid' => $issue_entity->id(),
          ))
          ->execute();
    }
  }

  /**
   * Get the mapped issues for a Node.
   *
   * @param \Drupal\Core\Entity\EntityInterface
   *
   *
   * @return array
   *   An array containing all the entites of the mapped issues.
   */
  public function getIssuesByEntity(EntityInterface $entity) {
    $inids = array();

    foreach ($this->getIssuesByNid($entity) as $inid) {
      $inids[] = CheckstyleIssue::load($inid);
    }

    return $inids;
  }

  public function getIssuesByNid(EntityInterface $entity) {
    $inids = array();
    $nid = $entity->id();
    $query = $this->connection->select('code_analyzer_project_issues', 'citm');
    $query->fields('citm', array('inid'));
    $query->condition('citm.nid', $nid);
    $results = $query->execute()->fetchAll();

    foreach ($results as $result) {
      $inids[] = $result->inid;
    }
    return $inids;
  }
}