<?php

/**
 * @file
 * Contains checkstyle_issue.page.inc..
 *
 * Page callback for Checkstyle issue entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Checkstyle issue templates.
 *
 * Default template: checkstyle_issue.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_checkstyle_issue(array &$variables) {
  // Fetch CheckstyleIssue Entity Object.
  $checkstyle_issue = $variables['elements']['#checkstyle_issue'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
