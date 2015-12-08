<?php

/**
 * @file
 * Contains \Drupal\checkstyle\CheckstyleIssueInterface.
 */

namespace Drupal\checkstyle;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Checkstyle issue entities.
 *
 * @ingroup checkstyle
 */
interface CheckstyleIssueInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

}
