<?php

/**
 * @file
 * Contains \Drupal\checkstyle\CheckstyleIssueAccessControlHandler.
 */

namespace Drupal\checkstyle;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Checkstyle issue entity.
 *
 * @see \Drupal\checkstyle\Entity\CheckstyleIssue.
 */
class CheckstyleIssueAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view checkstyle issue entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit checkstyle issue entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete checkstyle issue entities');
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add checkstyle issue entities');
  }

}
