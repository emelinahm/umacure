<?php

  /* Single Posts */

  if (!defined('ABSPATH')) die();

  global $der_framework; 
  
  get_header();

  $post_type = get_post_type();
  
  if ($post_type == 'portfolio' && $der_framework->has_layout('portfolio_layout')) {
    
    $der_framework->render_layout();  // portfolio posts bulk layout
  
  } else if ($der_framework->has_layout('single_layout')) {
    
    $der_framework->render_layout();  // single posts bulk layout

  } else {

    theme_default_content();
    
  }

  get_footer();
  
?>