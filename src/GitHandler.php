<?php
/**
 * Created by PhpStorm.
 * User: legovaer
 * Date: 04/12/15
 * Time: 12:53
 */

namespace Drupal\code_analyzer;

use Gitonomy\Git\Repository;
use Gitonomy\Git\Admin;


class GitHandler {

  const DRUPAL_GIT_BASE_URL = 'http://git.drupal.org/project';

  /**
   * Clone a module from drupal.org.
   *
   * @param string $project_name
   *   The (machine) name of the module/project that needs to be downloaded.
   * @param string $branch
   *   The branch that needs to be cloned. By default we will use the 8.x-1.x
   *   branch.
   */
  public static function retrieveModule($project_name, $branch = "8.x-1.x") {
    $branch = "origin/" . $branch;
    $path = self::getProjectDir($project_name);
    $url = self::DRUPAL_GIT_BASE_URL . '/' .$project_name;

    // Check if the repository is still available in the tmp folder.
    if (file_exists($path)) {
      $repository = new Repository($path);
    }
    else {
      $repository = Admin::cloneTo($path, $url, false);
    }
    try {
      $repository->getWorkingCopy()->checkout($branch);
      drupal_set_message(t('Successfully cloned branch @branch of @module.', array('@branch' => $branch, '@module' => $project_name)));
    }
    catch (\Exception $e) {
      drupal_set_message(t('Something went wrong while cloning the repository. Please check the logs for more information.'), 'error');
      watchdog_exception('checkstyle', $e);
    }
  }

  public static function getProjectDir($project_name) {
    return file_directory_temp() . "/" . $project_name;
  }

}
