Drupalytics
===========

Drupalytics is a Drupal 8 distribution that will allow you to perform a full
analysis on Drupal modules. 

Checks that are currently done by Drupalytics: checkstyle.

**Warning:** The code inside this project is experimental and should not be used
on any production environment!

## Notes
* The code inside this project is experimental
* Currently, kicking off a test is hardcoded by opening this URL: 
'/code_analyzer'

## Prerequisites
* [Drupal Console](https://www.drupal.org/project/console) has been installed.
* Apache / MySQL is installed locally
* You have created a MySQL user/database for Drupalytics

## Installation
There are several steps you will need to complete in order to get the module
working:

1. Create a new project folder inside your http server's root directory.
   (E.g. `/var/www/drupalytics`)
3. Open your project folder and clone the Drupalytics repository into 
   `profiles/drupalytics` by executing:
   ```
   $ git clone git@github.com:Capgemini/drupalytics.git profiles/drupalytics
   ```
4. Now start installing Drupalytics by executing:
   ```
   $ sh profiles/drupalytics/scripts/install.sh
   ```
   As soon as all the required files have been downloaded, the installation
   script will ask you some details about your Drupal installation. After
   entering the details, Drupal will complete its installation.
5. Drupalytics has now been installed, open your browser and have a look.
