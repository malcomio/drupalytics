api = 2
core = 8.x

; install Drupal core
projects[drupal][type] = core
projects[drupal][download][type] = git
projects[drupal][download][tag] = 8.0.1

; install contributed modules
projects[coder][download][type] = "git"
projects[coder][download][url] = "http://git.drupal.org/project/coder.git"
projects[coder][download][tag] = 8.x-2.5
projects[coder][download][interactive] = true
projects[coder][type] = "module"
projects[coder][subdir] = "contrib"
projects[coder][overwrite] = TRUE

projects[composer_manager][download][type] = "git"
projects[composer_manager][download][url] = "http://git.drupal.org/project/composer_manager.git"
projects[composer_manager][download][tag] = 8.x-1.0-rc1
projects[composer_manager][download][interactive] = true
projects[composer_manager][type] = "module"
projects[composer_manager][subdir] = "contrib"
projects[composer_manager][overwrite] = TRUE

projects[geshifilter][download][type] = "git"
projects[geshifilter][download][url] = "http://git.drupal.org/project/geshifilter.git"
projects[geshifilter][download][branch] = 8.x-1.x
projects[geshifilter][download][interactive] = true
projects[geshifilter][type] = "module"
projects[geshifilter][subdir] = "contrib"
projects[geshifilter][overwrite] = TRUE