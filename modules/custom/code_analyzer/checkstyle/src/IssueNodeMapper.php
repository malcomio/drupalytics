<?php

/**
 * @file
 * Contains \Drupal\checkstyle\IssueNodeMapper
 */

namespace Drupal\checkstyle;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

/**
 * Defines an object that will map CheckstyleIssues with CheckstyleTypes.
 *
 * @in-group checkstyle
 */
class IssueNodeMapper {
  /**
   * Constructs the forum manager service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Database\Connection $connection
   *   The current database connection.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation manager service.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * Get the checkstyle_type id for a given type.
   *
   * @param string $type
   *
   * @return int
   */
  public function getCheckstyleTypeId($type) {
    if ($this->issueTypeExists($type)) {
      $type_id = $this->getCheckstyleType($type)->id();
    }
    else {
      $type_id = $this->createIssueType($type)->id();
      //$type_id = 3;
    }
    return $type_id;
  }

  /**
   * Creates a new node of checkstyle_type.
   *
   * @param string $type
   *
   * @return Node
   */
  public function createIssueType($type) {
    $checkstyle_type = Node::create(['issue_type' => $type, 'type' => 'checkstyle_type', 'title' => $type]);
    $checkstyle_type->save();

    $this->connection->insert('checkstyle_issue_type_mapping')
      ->fields(array(
        'nid' => $checkstyle_type->id(),
        'type' => $type,
      ))
      ->execute();
    return $checkstyle_type;
  }

  /**
   * Checks if the checkstyle type exists.
   *
   * @param string $type
   *
   * @return bool
   */
  public function issueTypeExists($type) {
    return is_object($this->getCheckstyleType($type));
  }

  /**
   * Get the checkstyle type entity for a specified type.
   *
   * @param string $type
   *   The type to look for.
   *
   * @return Node|NULL
   *   The Node object if the type exists, otherwise NULL.
   */
  public function getCheckstyleType($type) {
    $query = $this->connection->select('checkstyle_issue_type_mapping', 'citm');
    $query->fields('citm', array('nid'));
    $query->condition('citm.type', $type);
    $itid = $query->execute()->fetchField();
    return Node::load($itid);
  }
}
