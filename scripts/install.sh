#!/bin/bash
# Drupalytics installation script
clear
echo "Starting Drupalytics installation. Grab some coffee, this can take a while."
echo ""
drush make profiles/drupalytics/drupalytics.make -y
drupal site:install drupalytics --site-name=Drupalytics --langcode=en
php modules/contrib/composer_manager/scripts/init.php
composer drupal-rebuild
composer update --lock
