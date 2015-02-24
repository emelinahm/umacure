<?php

  /* Theme Header */
  
  global $der_framework;
  
  $grid = $der_framework->option('content_width'); // Default is set

  switch ($grid) {
    case '960': $grid = 980; break;
    case '1170': $grid = 1200; break;
  }
  
  if ($der_framework->option_bool('webfonts_enabled')) add_action('wp_print_styles', 'theme_typography_styles');

  $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
  
  if ($ua && preg_match('/MSIE 8/', $ua)) {
    wp_enqueue_script('ie8-compat-es5', $der_framework->uri('core/js/legacy/es5-shim.min.js'), array(), THEME_VERSION);
    wp_enqueue_script('ie8-compat-html5', $der_framework->uri('core/js/legacy/html5.min.js'), array(), THEME_VERSION);
  }

  wp_enqueue_style('meteor-grid', $der_framework->uri("core/css/grid/${grid}.css"), array(), THEME_VERSION);

  if (THEME_RELEASE) {
    wp_enqueue_style('meteor-core', $der_framework->get_theme_stylesheet(), array('meteor-grid'), THEME_VERSION);
  } else {
    wp_enqueue_style('meteor-core', $der_framework->uri("core/meteor-core.css"), array('meteor-grid'), THEME_VERSION);
  } 

  wp_enqueue_style('meteor-typography', $der_framework->uri('core/typography.css'), array('meteor-core'), THEME_VERSION);
  
  if ( is_singular() && comments_open() && (get_option('thread_comments') == 1) ) { 
    wp_enqueue_script('comment-reply', $in_footer=false); 
  }
  
  wp_enqueue_script('json2');
  wp_enqueue_script('jquery');
  wp_enqueue_script('head.js', $der_framework->uri("core/js/lib/head.load.min.js"), array('jquery'), THEME_VERSION, false);
  
?>