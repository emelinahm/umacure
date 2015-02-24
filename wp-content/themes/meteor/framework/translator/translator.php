<?php

  if (!defined('ABSPATH')) die();

  /* Core Po Editor */
  
  add_action('admin_menu', 'translator_register_screen');
  add_action('wp_ajax_translator_action', 'translator_action_callback');
  
  /* Registers the translator admin menu */
  
  function translator_register_screen() {
    __meteor_submenu_page('theme-options', "Translator", "Translator", THEME_CAPABILITY, 'translator', 'translator_render_page');
  }

  /* Renders the po editor page */
  
  function translator_render_page() { global $der_framework;
    include(TEMPLATEPATH . '/framework/translator/translator-locales.php');
    include(TEMPLATEPATH . '/framework/translator/translator-interface.php');
  }
  
  /* Translator action callback */
  
  function translator_action_callback() {
    require(TEMPLATEPATH . '/framework/translator/translator-action.php');
  }

?>