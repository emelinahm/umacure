<?php

  /* Pages */

  if (!defined('ABSPATH')) die();

  global $der_framework; 
  
  get_header();

  if ($der_framework->has_layout('page_layout')) {

    $der_framework->render_layout();

  } else {

    theme_default_content();
    
  }

  get_footer();
  
?>