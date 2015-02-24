<?php

  /* Theme Init */

  global $der_framework;
  
  // Theme support features
  add_theme_support('automatic-feed-links');
  add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
  
  // Load custom post types
  $der_framework->load_post_types('post, page, icon-post, gallery, pricing, portfolio');
  
  // Register custom nav menus
  register_nav_menus(array(
    'header-navigation' => __("Header Navigation", "theme")
  ));
  
  // Add child theme style.css
  if (is_child_theme()) {
    wp_enqueue_style('child', get_stylesheet_directory_uri() . '/style.css');
  }
  
?>