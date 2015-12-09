<?php

/**
 * @file
 * Contains \Drupal\checkstyle\Entity\CheckstyleIssue.
 */

namespace Drupal\checkstyle\Entity;

use Drupal\checkstyle\CheckstyleGeshiProcessor;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\checkstyle\CheckstyleIssueInterface;
use Drupal\node\Entity\Node;
use Drupal\user\UserInterface;

/**
 * Defines the Checkstyle issue entity.
 *
 * @ingroup checkstyle
 *
 * @ContentEntityType(
 *   id = "checkstyle_issue",
 *   label = @Translation("Checkstyle issue"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\checkstyle\CheckstyleIssueListBuilder",
 *     "views_data" = "Drupal\checkstyle\Entity\CheckstyleIssueViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\checkstyle\Entity\Form\CheckstyleIssueForm",
 *       "add" = "Drupal\checkstyle\Entity\Form\CheckstyleIssueForm",
 *       "edit" = "Drupal\checkstyle\Entity\Form\CheckstyleIssueForm",
 *       "delete" = "Drupal\checkstyle\Entity\Form\CheckstyleIssueDeleteForm",
 *     },
 *     "access" = "Drupal\checkstyle\CheckstyleIssueAccessControlHandler",
 *   },
 *   base_table = "checkstyle_issue",
 *   admin_permission = "administer CheckstyleIssue entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/checkstyle_issue/{checkstyle_issue}",
 *     "edit-form" = "/admin/checkstyle_issue/{checkstyle_issue}/edit",
 *     "delete-form" = "/admin/checkstyle_issue/{checkstyle_issue}/delete"
 *   },
 *   field_ui_base_route = "checkstyle_issue.settings"
 * )
 */
class CheckstyleIssue extends ContentEntityBase implements CheckstyleIssueInterface {
  use EntityChangedTrait;
  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
    $values['issue_type'] = \Drupal::service('checkstyle.issue.nodemapper')->getCheckstyleTypeId($values['issue_type']);
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * Get the affected source code.
   *
   * @return string
   *   The affected source code.
   */
  public function getSourceCode() {
    return $this->get('code')->value;
  }

  /**
   * Get the source (sniff).
   *
   * @return string
   *   The source (sniff) of the issue.
   */
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * Get the name of the type of the issue.
   *
   * @return string
   *   The name of the type of the issue.
   */
  public function getTypeName() {
    $type_entity = Node::load($this->getTypeId());
    return $type_entity->get('title')->value;
  }

  /**
   * Get the Entity ID of the type of the issue.
   *
   * @return int
   *   The Entity ID of the type of the issue.
   */
  public function getTypeId() {
    return $this->get('issue_type')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Checkstyle issue entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Checkstyle issue entity.'))
      ->setReadOnly(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Checkstyle issue entity.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['severity'] = BaseFieldDefinition::create('integer');
    $fields['relative_path'] = BaseFieldDefinition::create('string');
    $fields['fixable'] = BaseFieldDefinition::create('boolean');
    $fields['line'] = BaseFieldDefinition::create('integer');
    $fields['issue_type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Issue Type'))
      ->setDescription(t('The issue type.'))
      ->setSetting('target_type', 'node')
      ->setSetting('handler_settings', ['target_bundles' => ['checkstyle_type' => 'checkstyle_type']]);

    $fields['display_lines'] = BaseFieldDefinition::create('integer');
    $fields['source'] = BaseFieldDefinition::create('string');

    return $fields;
  }

  /**
   * Get the line number of the line that needs to be highlighted.
   *
   * @return int
   *   The number of the line.
   */
  public function getHightlightLine() {
    return $this->get('line')->value;
  }

  /**
   * Get the relative path of the file that has been analysed.
   *
   * @return string
   *   The relative path of the file.
   */
  public function getRelativePath() {
    return $this->get('relative_path')->value;
  }

  /**
   * Get the message of the issue.
   *
   * @return string
   *   The message of the issue.
   */
  public function getMessage() {
    return $this->get('message')->value;
  }

  /**
   * Get the amount if lines that need to be displayed.
   *
   * This has been set on a database level, because the configuration might
   * have changed by the time that we are displaying the results. If this value
   * is not stored, we might be highlighting the wrong line.
   *
   * @return int
   *   The amount of lines that need to be displayed before and after the
   *   highlighted line.
   */
  public function getDisplayLines() {
    return $this->get('display_lines')->value;
  }

}
