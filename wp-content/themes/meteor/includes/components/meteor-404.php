<?php

  function meteor_404($atts=array(), $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'icon' => 'icon-warning-sign',
      'error_label' => __("We Couldn't Find Your Page (404 Error)", "theme"),
      'error_description' => __("It appears the page you're looking for can't be displayed", "theme") . '. ' . __("Try searching the site", "theme") . ':',
      'search_text' => __("Search", "theme"),
      'home_url' => home_url(),
      'home_btn_icon' => "icon-home",
      'back_to_homepage' => __("Back to Homepage", "theme")
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    return $der_framework->render_template('meteor-404.mustache', $args);
    
  }

?>