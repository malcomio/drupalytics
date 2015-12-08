<?php

/**
 * @file
 * Contains \Drupal\checkstyle\Entity\CheckstyleIssue.
 */

namespace Drupal\checkstlye\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Checkstyle issue entities.
 */
class CheckstyleIssueViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['checkstyle_issue']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Checkstyle issue'),
      'help' => $this->t('The Checkstyle issue ID.'),
    );

    return $data;
  }

}
