<?php

  /* Theme Admin */
  
  global $der_framework, $content_width;
  
  // Filters
  add_filter('mce_buttons', "extended_editor_mce_buttons", 0);
  add_filter('mce_buttons_2', "extended_editor_mce_buttons_2", 0);
  
  // Actions
  add_action('mce_css', 'theme_editor_webfonts', 10, 2);
  add_action('admin_init', 'theme_maybe_rebuild_typography');
  add_action('admin_print_styles', 'theme_admin_extras_styles');
  add_action('admin_print_styles', 'theme_admin_fontawesome');
  add_action('admin_notices', 'theme_admin_notice');
  add_action('theme_options_save', 'theme_get_logo_data', 10, 2);
  add_action('framework_save_meta', 'custom_posts_save_meta', 10, 1);
  add_action('after_switch_theme', 'theme_rebuild_typography', 10, 0);
  add_action('theme_options_save', 'theme_rebuild_color_theme', 10, 2);
  add_action('theme_options_before_save', 'theme_update_typography_stylesheet', 10, 2);
  
  // Set content width if it's not set
  $content_width = (int) $der_framework->option('content_width');
  
  // Set gallery thumb image
  add_image_size('gallery-manager-thumb', GI_THUMB_WIDTH, GI_THUMB_HEIGHT, true);
  
  // Editor styles
  add_editor_style('core/css/editor.css');
  
  // Loads custom metaboxes
  $der_framework->load_metaboxes('post, page, icon-post, portfolio, gallery, pricing');
  
  // Load option pages
  $der_framework->option_pages(array());
  
  /* Functions */
  
  function theme_admin_notice() { global $pagenow, $der_framework;
    
    if ($pagenow == 'options-reading.php') {
      
      // Homepage Layout is set
    
      $homepage_layout = $der_framework->option('homepage_layout');
      
      if ($homepage_layout && $der_framework->layout_exists($homepage_layout)) {
        
        printf('<div class="updated" style="margin: 1em 0">
<p>Your Homepage has the <strong>%s</strong> layout assigned.&nbsp; 
This prevents you from setting the <u>Front page displays</u> setting to something other than <u>Your latest posts</u>.</p>
<p>To be able to change the <u>Front page displays</u> option, unset the <strong>Homepage Layout</strong> from <a href="admin.php?page=theme-options#layouts">Theme Options &rarr; Layouts</a>.</p>
</div>', esc_html($homepage_layout));

      } else {
        
        // Static page is set, Homepage Layout not set
      
        $front = get_option('show_on_front');
        $front_page = get_option('page_on_front');
        
        if ($front == 'page' && $front_page) {
          
          printf('<div class="updated" style="margin: 1em 0">
<p><strong>Warning</strong>: You have selected a <u>static page</u> to be shown as your <u>Front page</u>.&nbsp;
This will <strong>ignore</strong> any layouts that have been set for <u>that page</u>.</p>
</div>', esc_html($homepage_layout));
          
        }
        
      }
      
    }

  }
  
  function theme_maybe_rebuild_typography() { global $der_framework;
    $stylesheet = $der_framework->path('core/typography.css');
    if (!@file_exists($stylesheet) && @is_writable(dirname($stylesheet))) theme_rebuild_typography();
  }

  function theme_editor_webfonts($mce_css) { global $der_framework;
    $webfonts = theme_typography_styles(true);
    $timestamp = $der_framework->get_option('typography_rebuild_timestamp');
    $webfonts['typography'] = $der_framework->uri('core/typography.css', $timestamp);
    foreach ($webfonts as $name => $url) {
      $mce_css .= ',' . str_replace(',', '%2C', $url);
    }
    return $mce_css;
  }
  
  function theme_update_typography_stylesheet($context, $options) { global $der_framework;
    if ($context === 'theme-options') theme_rebuild_typography($options);
  }

  function theme_rebuild_color_theme($context, $options) {
    if (preg_match('/^color-editor_/', $context)) {
      exit('success:rebuild-color-theme');
    }
  }
  
  function theme_admin_extras_styles() { global $der_framework, $pagenow;
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'revslider') {
      $webfonts = theme_typography_styles(true);
      foreach ($webfonts as $font => $v) {
        printf('<link rel="stylesheet" type="text/css" href="%s" />'."\n", $v);
      }
      if (isset($font)) {
        printf("\n".'<style type="text/css">.tp-caption { font-family: "%s", sans-serif !important; }</style>'."\n\n", $font);
      }
    }
  }
  
  function theme_admin_fontawesome() { global $der_framework;
    wp_enqueue_style('fontawesome', $der_framework->uri('core/fonts/fontawesome/webfont.css'), array(), THEME_VERSION);
  }
  
  function custom_posts_save_meta($keys) { global $der_framework;
    // Makes sure the 'order' meta key is set
    $order_key = $der_framework->key('order');
    if (empty($_POST[$order_key])) {
      $_POST[$order_key] = 1;
    }
  }
  
  function theme_get_logo_data($context, $options) { global $der_framework;
    
    if ($context == 'theme-options') {
      
      $logo_image = isset($options['logo_image']) ? $options['logo_image'] : null;
      $retina_logo_image = isset($options['retina_logo_image']) ? $options['retina_logo_image'] : null;
      
      if ($logo_image && $logo_image != $der_framework->option('logo_image')) {
        $info = @getimagesize($options['logo_image']);
        if ($info) {
          $der_framework->update_option('logo_image_size', array_slice($info, 0, 2));
        } else {
          $der_framework->delete_option('logo_image_size');
        }
      } else if ($logo_image == null) {
        $der_framework->delete_option('logo_image_size');
      }
      
      if (!empty($retina_logo_image) && $retina_logo_image != $der_framework->option('retina_logo_image')) {
        $info = @getimagesize($options['retina_logo_image']);
        if ($info) {
          $der_framework->update_option('retina_logo_image_size', array_slice($info, 0, 2));
        } else {
          $der_framework->delete_option('retina_logo_image_size');
        }
      } else if ($retina_logo_image == null) {
        $der_framework->delete_option('retina_logo_image_size');
      }
      
    }

  }
  
  function extended_editor_mce_buttons($buttons) { global $post_type;
    if ($post_type == 'pricing') {
      return array(
        "undo", "redo", "separator",
        "bold", "italic", "underline", "strikethrough", 
        "separator", "separator",
        "charmap", "separator", "link", "unlink", "anchor", "separator",
        "separator", "separator",
        "search", "replace", "separator", "wphelp"
      );
    } else {
      return $buttons;
    }
  }

  function extended_editor_mce_buttons_2($buttons) { global $post_type;
    if ($post_type == 'pricing') {
      return array();
    } else {
      return $buttons;
    }
  }
  
?>