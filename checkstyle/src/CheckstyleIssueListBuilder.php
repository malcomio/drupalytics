<?php

/**
 * @file
 * Contains \Drupal\checkstyle\CheckstyleIssueListBuilder.
 */

namespace Drupal\checkstyle;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Field\EntityReferenceFieldItemList;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Defines a class to build a listing of Checkstyle issue entities.
 *
 * @ingroup checkstyle
 */
class CheckstyleIssueListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Checkstyle issue ID');
    $header['issue_type'] = $this->t('Issue type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\checkstyle\Entity\CheckstyleIssue */
    $row['id'] = $this->l(
      $entity->id(),
      new Url(
        'entity.checkstyle_issue.edit_form', array(
          'checkstyle_issue' => $entity->id(),
        )
      )
    );
    /** @var EntityReferenceFieldItemList $ref_field */
    $ref_field = $entity->get('issue_type');
    /** @var Node $ref_entity */
    $ref_entity = Node::load($ref_field->get(0)->getValue()['target_id']);
    krumo($ref_entity->get('issue_type')->getValue()[0]['value']);
    #krumo( $ref_entity->get('title'));
    # @todo find decent way of displaying this shit
    $row['issue_type'] = $ref_entity->get('issue_type')->getValue()[0]['value'];
    return $row + parent::buildRow($entity);
  }

}
