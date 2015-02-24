<?php

  if (!defined('ABSPATH')) die();

  global $pagenow;
  
  /* Core Admin Functions */
  
  // Core actions
  add_action('admin_menu', 'theme_register_options_page');
  add_action('after_switch_theme', 'theme_activation_hook');
  add_action('admin_notices', 'detect_permission_issues');
  add_action('init', 'theme_color_themes_backup');
  
  // Admin ajax calls
  add_action('wp_ajax_sendmsg_submit', 'sendmsg_submit');
  add_action('wp_ajax_nopriv_sendmsg_submit', 'sendmsg_submit');
  add_action('wp_ajax_nopriv_twitter_api', 'twitter_api_ajax');
  add_action('wp_ajax_nopriv_flickr_json', 'flickr_json_ajax');
  add_action('wp_ajax_theme_options_update', 'theme_options_update_callback');
  add_action('wp_ajax_theme_options_misc', 'theme_options_misc_callback');
  add_action('wp_ajax_webfonts_update', 'webfonts_update_callback');
  
  // Theme admin interface assets
  add_action('admin_print_styles', 'theme_admin_stylesheet');
  add_action('admin_print_scripts', 'theme_admin_print_scripts');
  add_action('admin_head', 'theme_admin_head');
  add_action('theme_options_reset', 'theme_options_reset');
  add_action('theme_options_hidden_fields', 'theme_options_hidden_fields');
  add_action('theme_options_render_head', 'theme_options_render_head');
  
  // Theme Options Context
  add_filter('theme_options_context', 'theme_options_context');
  
  // Restore operations
  add_filter('theme-options-save', 'layout_editor_restore');
  add_filter('theme-options-save', 'color_themes_restore');
  
  // Allow thickbox uploader to show different labels
  if ($pagenow == 'media-upload.php') {
    if (!isset($_REQUEST['post_id'])) $_REQUEST['post_id'] = 0;
    add_filter('gettext', 'theme_thickbox_labels');
  }
  
  /* WP Ajax Calls */
  
  function twitter_api_ajax() { global $der_framework;
    require($der_framework->path('framework/twitter-api.php'));
  }
  
  function flickr_json_ajax() { global $der_framework;
    require($der_framework->path('framework/flickr-json.php'));
  }
  
  /* Detect permission issues */
  
  function detect_permission_issues() { global $der_framework;
    $failed = array();
    $effects = array();
    $paths = array(
      'core/',
      'cache/mustache',
      'styles/',
      'includes/languages/'
    );
    foreach ($paths as $path) {
      $p = $der_framework->path($path);
      if (!is_writable($p)) {
        $failed[] = $path;
        switch ($path) {
          case 'core/':
            $effects[] = "Web Fonts will not be applied correctly, since the typography stylesheet can't be written to disk.";
            break;
          case 'cache/mustache/':
            $effects[] = "Nothing will display (except header/footer), since the templates can't be written to disk.";
            break;
          case 'styles/':
            $effects[] = "The Color Editor will not be able to save the generated stylesheets, since they can't be written to disk.";
            break;
          case 'includes/languages/':
            $effects[] = "Translation files will not be able to be saved, since they can't be written to disk.</strong>";
            break;
        }
      }
    }
    
    if (!empty($failed)) {
  
      for ($i=0; $i < count($effects); $i++) {
        $effects[$i] = sprintf('<span style="display: inline-block; color: #d20f0f; margin-top: 0.5em">- %s</span>', $effects[$i]);
      }
      
      printf('<div class="error" style="padding: 5px 5px;">
<h3 style="margin: 0.4em 0 0 0;">Permission Issues</h3>
<p>The theme needs the following paths to be <strong>writable</strong>. Currently, they are not:</p>
<pre><strong>%s</strong></pre>
<p>If these directories are not writable, The theme <strong>will not</strong> function properly. This is <strong>NOT</strong> a bug, it has to do with your server.</p>
<p style="padding: 10px; border: solid 1px #E6DB55; background: #ffffe0; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin: 0 5px;">
<strong>To fix this</strong>, log into your site via FTP and set the permissions <u>of the directories listed above</u> to 777
</p>
<p>Your site will have the following problems if the issues above are not addressed:</p>
<p style="margin-bottom: 1em;"><em>%s</em></p>
<p>The theme would love to work properly, but currently It can\'t.</p>
</div>', implode("\n", $failed), implode("<br/>", $effects));

    }
    
    if (!function_exists('imagecreatetruecolor')) {
      
      printf('<div  class="error" style="padding: 5px 5px;">
<h3 style="margin: 0.4em 0 0 0;">Thumbnails Issue</h3>
<p>It appears your server doesn\'t have the <strong><a target="_blank" href="http://php.net/manual/en/image.installation.php">PHP GD Library</a></strong> installed. The library is needed for the image cropping/resizing on your site. If the library is not installed,
you will not be able to see any thumbnails on your site. <strong>This is not a BUG</strong>.</p>
<p>You can ask your hosting provider to enable/install the library on your Server\'s PHP Configuration. For additional help, refer to this <a target="_blank" href="http://www.google.com/search?ie=UTF-8&q=php+enable+gd+library&oq=php+enable+gd+library">google search</a>.</p>
</div>');

    }
    
    if (!function_exists('mcrypt_encrypt')) {
      
      printf('<div  class="error" style="padding: 5px 5px;">
<h3 style="margin: 0.4em 0 0 0;">Contact Forms Issue</h3>
<p>Unable to find the <strong><a target="_blank" href="http://php.net/manual/en/function.mcrypt-encrypt.php">mcrypt_encrypt</a></strong> function. The function is used to encrypt your information when rendering contact forms using the Form Builder. This prevents
the disclosure of your email, both for privacy and to protect you from Spam Bots. <strong>This is not a BUG</strong>.</p>
<p>You can ask your hosting provider to enable the <strong><a target="_blank" href="http://www.php.net/manual/en/intro.mcrypt.php">MCrypt Extension</a></strong> on your Server\'s PHP Configuration. For additional help, refer to this <a target="_blank" href="https://www.google.com/search?ie=UTF-8&q=php+enable+mcrypt">google search</a>.</p>
</div>');

    }
    
  }
  
  /* Color themes backup */
  
  function theme_color_themes_backup() { global $der_framework, $pagenow;
    if (is_admin() && $pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'color-themes-backup' && current_user_can(THEME_CAPABILITY)) {
      $color_themes = $der_framework->get_color_themes();
      $out = array(
        'themes' => $color_themes,
        'data' => array()
      );
      foreach ($color_themes as $id => $name) {
        $data = $der_framework->get_color_theme_data($id, true);
        $out['data'][$id] = $data;
      }
      $filename = string2id(get_bloginfo('name')) . '-colorthemes.json';
      header('Content-Type: application/json;charset=utf-8');
      header("Content-Disposition: attachment;filename=${filename}");
      exit(json_encode($out));
    }
  }
  
  /* Theme Options Hidden Fields */
  
  function theme_options_hidden_fields($context) { global $der_framework;
    $context = $der_framework->get_context();
    if (preg_match('/^color-editor_/', $context)) {
      $context_key = $der_framework->context_key();
      $mappings = $der_framework->color_theme_keys;
      $opts = $der_framework->context_options('theme-options', array_keys($mappings));
      printf("\n".'<!-- Color Theme Variables -->');
      foreach ($opts as $k => $v) {
        if (isset($v)) printf("\n".'<input type="hidden" name="%s[%s]" value="%s" />', $context_key, $mappings[$k], $v);
      }
      echo "\n\n";
    }
  }
  
  /* Reset theme options hook */
  
  function theme_options_reset($context) { global $der_framework;
    if ($context === 'color-editor_default') {
      $style = $der_framework->get_color_theme_stylesheet('default');
      @copy($style . '.orig', $style);
    }
  }
  
  /* Theme Options Context filter */
  
  function theme_options_render_head($context) { global $der_framework;
    $context = $der_framework->get_context();
    if (preg_match('/^color-editor_/', $context)) {
      $color_theme_name = $der_framework->get_color_theme_name();
      printf('<p style="padding: 0 0 1.1em; margin-bottom: 1.8em !important; border-bottom: dashed 1px #ccc;">
        Color Theme: &nbsp; <strong><i class="icon-ok" style="color: green;"></i>&nbsp; %s</strong> &nbsp;&mdash;&nbsp; <small><a class="notd" href="admin.php?page=theme-options#general">Change</a></small>
</p>', $color_theme_name);
    }
  }
  
  function theme_options_context($context) { global $der_framework;
    if ($context == 'color-editor') {
      $context .= '_' . $der_framework->get_color_theme();
    }
    return $context;
  }

  /* Webfonts update callback */
  
  function webfonts_update_callback() {
    require(TEMPLATEPATH . '/framework/webfonts/update.php');
  }
  
  /* Theme Options update callback */
  
  function theme_options_update_callback() {
    require(TEMPLATEPATH . '/framework/theme-options/theme-options-action.php');
  }
  
  /* Theme Options Misc. callback */
  
  function theme_options_misc_callback() {
    require(TEMPLATEPATH . '/framework/theme-options/theme-options-misc.php');
  }
  
  /* Sendmsg submit action */
  
  function sendmsg_submit() {
    require(TEMPLATEPATH . '/framework/mailer/sendmsg.php');
  }
  
  /* Theme Thickbox labels */
  
  function theme_thickbox_labels($translated, $text='', $domain='') {
    if ($translated == "Insert into Post") {
      if (isset($_GET['label'])) {
        switch ($_GET['label']) {
          case 'chooseThisImage':
            return "Choose Media";
            break;
          default:
            return $translated;
            break;
        }
      } else {
        return $translated;
      }
    } else {
      return $translated;
    }
  }
  
  /* Theme Options Page */
  
  function theme_options_page() { global $der_framework;
    $context = $_GET['page'];
    if ($context != 'layout-editor') {
      define('THEME_OPTIONS', true);
      $der_framework->set_context($context);
      require($der_framework->path('framework/classes/class.theme-options.php'));
      include_once($der_framework->path(sprintf('includes/options/%s.php', $context)));
      new ThemeOptions();
      $der_framework->reset_context();
    }
  }
  
  /* Register theme options page */
  
  function theme_register_options_page() { global $der_framework, $theme_admin_menus;
    
    // Add theme options page
    __meteor_menu_page(null, $der_framework->theme_data->get('Name'), THEME_CAPABILITY, 'theme-options', 'theme_options_page', $der_framework->uri('core/images/admin/admin-menu-icon.png', THEME_VERSION), 56 );
    
    // Add submenu pages
    __meteor_submenu_page('theme-options', 'Theme Options', 'Theme Options', THEME_CAPABILITY, 'theme-options', 'theme_options_page');
    if (is_array($theme_admin_menus)) {
      foreach ($theme_admin_menus as $slug => $title) {
        __meteor_submenu_page('theme-options', $title, $title, THEME_CAPABILITY, $slug, 'theme_options_page');
      }
    }
    
    // Add color editor page
    __meteor_menu_page("Color Editor", "Colors", THEME_CAPABILITY, 'color-editor', 'theme_options_page', $der_framework->uri('framework/assets/color-editor.png', THEME_VERSION), 58 );
    
  }
  
  /* Renders admin styles */

  function theme_admin_stylesheet() { global $der_framework;
    wp_enqueue_style('framework-admin', $der_framework->uri('framework/assets/css/admin.css'), array(), THEME_VERSION);
    wp_enqueue_style('framework-theme-options', $der_framework->uri('framework/theme-options/theme-options.css'), array(), THEME_VERSION);
    wp_enqueue_style('framework-prettyphoto', $der_framework->uri('framework/assets/prettyPhoto/css/prettyPhoto.css'), array(), THEME_VERSION);
    wp_enqueue_style('framework-prettyphoto', $der_framework->uri('framework/assets/prettyPhoto/css/prettyPhoto.css'), array(), THEME_VERSION);
    wp_enqueue_style('framework-colorpicker', $der_framework->uri('framework/assets/colorpicker/css/colorpicker.css'), array(), THEME_VERSION);
  }
  
  /* Renders the admin scripts */
  
  function theme_admin_print_scripts() { global $der_framework, $wp_version;
    
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    
    $update_server = (defined('THEME_UPDATE_SERVER')) ? THEME_UPDATE_SERVER : '';
    $update_check_interval = $der_framework->option('update_check_interval', 'weekly');
    
    // Encode JSON, prevents users from breaking admin
    $tf_data = base64_encode(json_encode($der_framework->options('tf_username, tf_purchase_code')));
    
    echo "
<script type='text/javascript'>
  document.WP_VERSION = '" . $wp_version . "';
  document.WP_THEME_ID = '" . THEME_ID . "';
  document.WP_HOME_URL = '" . get_home_url() . "';
  document.WP_ADMIN_URL = '" . admin_url() . "';
  document.WP_THEME_NAME = '" . $der_framework->theme_data->get('Name') . "';
  document.WP_THEME_VERSION = '" . $der_framework->theme_data->get('Version') . "';
  document.WP_UPDATE_SERVER = '" . $update_server . "';
  document.WP_UPDATE_CHECK_INTERVAL = '" . $update_check_interval . "';
  document.WP_THEME_URI = '" . $der_framework->uri('') . "';
  document.TF_DATA = '" . $tf_data . "';
  document.GI_THUMB_WIDTH = " . GI_THUMB_WIDTH . ";
  document.GI_THUMB_HEIGHT = " . GI_THUMB_HEIGHT . ";
  document.WP_I18N_COMMON = {
    newUpdateAvailable: '" . 'new update available' . "',
    documentation: '" . 'Documentation' . "',
    supportForums: '" . 'Support Forums' . "',
    getItNow: '" . 'Get it now!' . "',
    updateInstructions: '" . 'Update Instructions' . "',
    isAvailable: '" . 'is available' . "',
    themeOptions: '" . 'Theme Options' . "',
    layoutEditor: '" . 'Layout Editor' . "',
    savedSettings: '" . "Saved Settings" . "',
    resetSettings: '" . "Settings Reset" . "',
    resetSettingsMessage: '" . "Settings will be reset. Proceed ?" . "',
    uploadImage: '" . "Upload Image" . "',
    removeImage: '" . "Remove Image" . "',
    versionAvailable: '" . "Version %s available" . "',
    add: '" . "Add" . "',
    cancel: '" . "Cancel" . "',
    rename: '" . "Rename" . "',
    confirmRemove: '" . "About to remove \"%s\". Proceed ?" . "',
    splitVertically: '" . "Split Vertically" .  "',
    threeColumns: '" . "Three Columns" . "',
    confirmSlotRemove: '" . "The whole container will be removed. Proceed ?" . "'
  };
</script>\n";

    if (is_multisite()) { global $blog_id;

      echo '
<script type="text/javascript">
  document.WP_MULTISITE_ID = ' . $blog_id . ';
</script>' . "\n\n";

    }
    
  }
  
  /* Prints head scripts */
  
  function theme_admin_head() { global $der_framework;
    wp_enqueue_script('framework-sanitizer', $der_framework->uri('framework/assets/js/sanitizer.js'), array(), THEME_VERSION);
    wp_enqueue_script('framework-jwerty', $der_framework->uri('framework/assets/js/jwerty.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('jquery-base64',	$der_framework->uri('framework/assets/js/jquery.base-sixty-four.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-hogan.js', $der_framework->uri('framework/assets/js/hogan.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-less.js', $der_framework->uri('framework/assets/js/less-1.3.3.min.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-prettyphoto', $der_framework->uri('framework/assets/prettyPhoto/jquery.prettyPhoto.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-colorpicker', $der_framework->uri('framework/assets/colorpicker/js/colorpicker.js'), array(), THEME_VERSION);
    wp_enqueue_script('framework-gallery-interface', $der_framework->uri('framework/assets/js/gallery-interface.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-admin', $der_framework->uri('framework/assets/js/admin.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-theme-options', $der_framework->uri('framework/theme-options/theme-options.js'), array('jquery'), THEME_VERSION);
    wp_enqueue_script('framework-theme-options-colorthemes', $der_framework->uri('framework/theme-options/theme-options-colorthemes.js'), array('jquery'), THEME_VERSION);
  }
  
  /* Theme activation hook */
  
  function theme_activation_hook() {
    setcookie(THEME_ID . '_rebuild_colortheme', 1, (time() + 7*24*3600)); // Cookie will expire in 1 week
    flush_rewrite_rules();
  }
  
  /* Returns the taxonomy links used on post listing columns */

  function theme_list_taxonomies_column($post_id, $taxonomy) {
    $genres = wp_get_post_terms($post_id, $taxonomy);
    $post_type = get_post_type($post_id);
    $links = array();
    foreach ($genres as $term) {
      $term_name = $term->name;
      $term_slug = $term->slug;
      $links[] = sprintf('<a href="%s">%s</a>', admin_url("edit.php?post_type=${post_type}&taxonomy=${taxonomy}&${taxonomy}=${term_slug}"), $term_name);
    }
    echo implode(', ', $links);
  }
  
  /* Returns taxonomy data using slug => title */
  
  function theme_get_taxonomy_data($taxonomy) {
    $out = array();
    $terms = get_terms($taxonomy, array('hide_empty'=>false));
    foreach ($terms as $tax) {
      $out[$tax->slug] = esc_html($tax->name);
    }
    return $out;
  }
  
  /* Automatically updates the gallery thumbs */
  
  function gallery_thumbs_auto_update() { global $der_framework;
    $images = json_decode($der_framework->postmeta('gallery_images'), true);
    if ( is_array($images) ) {
      foreach ($images as $img) {
        $img = preg_replace("/\?.+$/", '', $img['image']);
        $der_framework->thumb_src($img, GI_THUMB_WIDTH, GI_THUMB_HEIGHT);
      }
    }
  }
  
  /* Restores the layout editor data */
  
  function layout_editor_restore($options) { global $der_framework;
    if (!empty($options['layout_data_restore'])) {
      $json = stripslashes($options['layout_data_restore']);
      $array = (array) json_decode($json, true);
      $der_framework->update_option('layouts', $array);
      $der_framework->update_option('layouts_json', $json);
    }
    unset($options['layout_data_restore']);
    return $options;
  }
  
  /* Restores the color themes data */
  
  function color_themes_restore($options) { global $der_framework;
    if (!empty($options['colorthemes_data_import'])) {
      $json = stripslashes($options['colorthemes_data_import']);
      $import_data = (array) json_decode($json, true);
      if (isset($import_data['themes']) && isset($import_data['data'])) {
        $color_themes = $der_framework->get_color_themes();
        foreach ($import_data['themes'] as $id => $name) {
          $color_themes[$id] = $name;
        }
        $der_framework->update_option('color_themes', $color_themes);
        foreach ($import_data['data'] as $id => $data) {
          $der_framework->update_option('color-editor_' . $id, $data);
        }
        exit('success:reload-options-page');
      }
    }
    unset($options['colorthemes_data_import']);
    return $options;
  }
  
?>