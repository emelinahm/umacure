<?php

  ////////////////////////////////////
  // DERDESIGN WORDPRESS FRAMEWORK
  ////////////////////////////////////

  if (!defined('ABSPATH')) die();

  // Theme debug setting.
  define('THEME_DEBUG', false);

  // Framework load path
  $framework_load = get_template_directory() . '/framework/load.php';
  /******p br 自動で補うのを抑制*******/
  remove_filter('the_content', 'wpautop');


  // Bootstrap the framework
  if (THEME_DEBUG) {
    if (!isset($_GET['noload'])) require($framework_load);
  } else {
    require($framework_load);
  }

?>