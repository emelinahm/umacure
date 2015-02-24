<?php

  if (!defined('ABSPATH')) die();
  
  /* Social Icons */
  
  add_action('admin_menu', 'social_icons_register_screen');
  add_action('wp_ajax_social_icons_update', 'social_icons_update_callback');
  
  if (isset($_GET['page']) && $_GET['page'] == 'social-icons') {
    add_action('admin_print_scripts', 'social_icons_print_scripts');
  }
  
  /* Update data */
  
  function social_icons_update_callback() {
    require(TEMPLATEPATH . '/framework/social-icons/social-icons-action.php');
  }
  
  /* Registers the social icons admin menu */
  
  function social_icons_register_screen() { global $der_framework;
    __meteor_submenu_page('theme-options', "Social Icons", 'Social Icons', THEME_CAPABILITY, 'social-icons', 'social_icons_render_page');
  }

  /* Renders the social icons page */
  
  function social_icons_render_page() { global $der_framework;
    include($der_framework->path('framework/social-icons/social-icons-interface.php'));
  }
  
  /* Load JS Scritps */
  
  function social_icons_print_scripts() { global $der_framework;
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('social-icons-interface',	$der_framework->uri('framework/social-icons/social-icons-interface.js'), array('jquery'), THEME_VERSION);
  }

?>