<?php

  /* Framework Bootstrap */
  
  if (!defined('ABSPATH')) die();

  global $der_framework, $theme_options;
  
  $template_directory = get_template_directory();
  
  // Add theme support
  add_theme_support('post-thumbnails');
  
  // Actions
  add_action('widgets_init', 'widget_init_callback');
  add_action('init', 'theme_init_callback');
  
  // Load compatibility functions
  require($template_directory . '/framework/compat/theme.php');

  // Theme Features
  load_theme_textdomain("theme", $template_directory . '/includes/languages');
  
  // Load theme config
  require($template_directory . '/includes/theme-config.php');
  
  // Load theme nonce values
  define('THEME_NONCE', THEME_ID . '_nonce');
  define('THEME_NONCE_VERIFY', THEME_NONCE . '_verify');

  // Common functions for both admin & site
  require($template_directory . '/framework/core-common.php');
  
  // Load core classes
  require($template_directory . '/framework/classes/class.theme.php');
  require($template_directory . '/framework/classes/class.metabox.php');
  require($template_directory . '/framework/classes/class.form-builder.php');
  require($template_directory . '/framework/classes/class.widget-framework.php');

  // Initialize the $der_framework instance
  $der_framework = new Theme();
  
  if ( is_admin() ) {
    
    /* Admin-only components */
    
    // Load core admin functions
    require($template_directory . '/framework/core-admin.php');
    
    // Load core metabox
    require($template_directory . '/framework/core-metabox.php');
  
    // Load layout editor
    require($template_directory . '/framework/layout-editor/layout-editor.php');
  
    // Load social icons manager
    require($template_directory . '/framework/social-icons/social-icons.php');
    
    // Load translator
    require($template_directory . '/framework/translator/translator.php');
    
    // Load admin functions
    require($template_directory . '/includes/theme-admin.php');
  
  } else {
  
    /* Site-only components */
  
    // Load core functions
    require($template_directory . '/framework/core-functions.php');
    
    // Load theme functions
    require($template_directory . '/includes/theme-functions.php');
  
  }

  /* Admin & Site components */
  
  // Load image resizer
  require($template_directory . '/framework/lib/mr-image-resize.php');
  
  // Load common functions
  require($template_directory . '/includes/theme-common.php');
  
?>