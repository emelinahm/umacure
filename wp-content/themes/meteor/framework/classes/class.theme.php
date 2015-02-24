<?php

$theme_sidebars = array();
$theme_admin_menus = array();
$theme_options = array();

// Internal content filters
add_filter('der_content', 'remove_empty_paragraphs', 9999);

// Default content filters
add_filter('__the_content', 'wptexturize');
add_filter('__the_content', 'convert_smilies');
add_filter('__the_content', 'convert_chars');
add_filter('__the_content', 'wpautop');
add_filter('__the_content', 'shortcode_unautop');

// Default excerpt filters
add_filter('__the_excerpt', 'wptexturize');
add_filter('__the_excerpt', 'convert_smilies');
add_filter('__the_excerpt', 'convert_chars');
add_filter('__the_excerpt', 'wpautop');
add_filter('__the_excerpt', 'shortcode_unautop');
add_filter('__the_excerpt', 'wp_trim_excerpt');

define('THEME_QUERY_POST_FORMATS', true);

class Theme {

  var $metabox;
  var $prefix;
  var $locales;
  var $theme_data;
  var $mustache;
  var $home_url;
  var $template_cache = array();
  
  var $layout;
  var $layouts;
  var $layout_keys;
  var $layout_to_render;
  var $options;
  var $default_options;
  var $options_context = 'theme-options';
  var $options_context_last = 'theme-options';
  
  var $runtime = null;
  var $last_query = null;
  var $tabs_context = null;
  var $in_footer = false;
  var $rendering_layout = false;
  var $load_maps_api = false;
  var $color_theme_keys = array();
  var $color_theme_options = null;
  var $query_post_formats = THEME_QUERY_POST_FORMATS;
  var $exclude_post_formats = null;
  
  private $rand;
  private $pagination_defaults;
  private $widget_defaults;
  private $template_directory_uri;
  private $more_tag = '<!--more-->';
  private $mustache_path;

  ///////////////////////////////////////////////////////
  // CONSTRUCTOR
  ///////////////////////////////////////////////////////
  
  function __construct() {
    
    // Set theme prefix
    $this->prefix = sprintf('_%s_', THEME_ID);
    
    // Cache home url
    $this->home_url = home_url();
    
    // Initialize pagination options
    $this->pagination_defaults = array(
      'container_open' => '<div class="pagination">',
      'container_close' => '</div><!-- pagination -->',
      'ul_class' => 'clearfix',
      'prev' => '',
      'next' => '',
      'page_class' => 'page',
      'active_class' => 'current',
      'nav_class' => 'nav',
      'prev_class' => 'prev',
      'next_class' => 'next',
      'rewind_class' => 'rewind',
      'fast_forward_class' => 'fast-forward',
      'nav' => true,
      'range' => 2
    );
    
    // Initialize widget defaults
    $this->widget_defaults = array(
      'name' => null,
      'id' => null,
      'description' => '',
      'before_widget' => "\n" . '<div class="widget %2$s" id="%1$s">' . "\n",
      'after_widget' => "\n</div><!-- .widget -->\n",
      'before_title' => '<h3 class="title">',
      'after_title' => '</h3>' . "\n"
    );
    
    // Set runtime object
    $this->runtime = new stdClass();
    
    // Template directory URI
    $this->template_directory_uri = get_template_directory_uri();

    // Theme data
    $this->theme_data = wp_get_theme(get_template());
    
    // Theme version
    if (!defined('THEME_VERSION')) define('THEME_VERSION', $this->theme_data->get('Version'));
    
    // Theme Options. Loaded as needed
    $this->options = array();
    
    // Default theme options. Loaded as needed
    $this->default_options = array();
    
    // Set layouts
    $this->layouts = $this->get_option_array('layouts');
    
    // Set layout keys
    $this->layout_keys = implode(',', array_keys($this->layouts));

    // Set layout instance
    require('class.layout.php');
    $this->layout = new Layout();
    
    // Load mustache engine
    $template_directory = get_template_directory();
    define('THEME_MUSTACHE_PATH', $this->path('includes/mustache'));
    require($template_directory . '/framework/mustache/mustache.php');
    $this->mustache = new Mustache_Engine(array(
      'cache' => $template_directory . '/cache/mustache',
      'partials_loader' => new Mustache_Loader_FilesystemLoader(THEME_MUSTACHE_PATH . '/partials'),
      'charset' => 'UTF-8',
      'escape' => 'esc_html'
    ));
    
  }
  
  ///////////////////////////////////////////////////////
  // METHODS
  ///////////////////////////////////////////////////////
  
  /* Returns a color theme option */

  function color_theme_option($context, $default=null) {
    if ($this->color_theme_options === null) {
      $this->color_theme_options = $this->get_option(sprintf('color-editor_%s', $this->get_color_theme()));
    }
    return (isset($this->color_theme_options[$context])) ? $this->color_theme_options[$context] : $default;
  }

  /* Prints the color theme stylesheet */
  
  function get_theme_stylesheet() {
    $id = $this->get_color_theme();
    $stylesheet = $this->get_color_theme_stylesheet($id);
    $id = (@file_exists($stylesheet)) ? $id : 'default';
    return $this->uri(sprintf('styles/%s.css', $id));
  }
  
  /* Gets the color themes available */
  
  function get_color_themes() {
    $out = $this->get_option('color_themes');
    return (is_array($out)) ? $out : array();
  }
  
  /* Gets the current color theme */
  
  function get_color_theme() {
    return $this->context_option('theme-options', 'color_theme');
  }
  
  /* Gets the current color theme data */
  
  function get_color_theme_data($current_theme=null, $data_only=false) {
    if (empty($current_theme)) $current_theme = $this->get_color_theme();
    $data = $this->get_option('color-editor_' . $current_theme);
    $mapping = $this->color_theme_keys;
    $opts = $this->context_options('theme-options', array_keys($mapping));
    foreach ($opts as $k => $v) if (isset($v)) $data[$mapping[$k]] = $v;
    return $data_only ? $data : array('data' => $data);
  }
  
  /* Sets color theme keys */
  
  function set_color_theme_keys($keys) {
    $this->color_theme_keys = $keys;
  }
  
  /* Gets a color theme stylesheet path */
  
  function get_color_theme_stylesheet($id) {
    return $this->path(sprintf('styles/%s.css', $id));
  }
  
  /* Updates the css code for a color theme */
  
  function update_color_theme_css($css) {
    $current_theme = $this->get_color_theme();
    $target = $this->path(sprintf('styles/%s.css', $current_theme));
    $css = @base64_decode($css);
    if ($css && is_string($css)) {
      @file_put_contents($target, $css);
    }
  }
  
  /* Gets the color theme name */
  
  function get_color_theme_name() {
    $id = $this->get_color_theme();
    if ($id === 'default') {
      return sprintf("%s Default", $this->theme_data->get('Name'));
    } else {
      $themes = $this->get_color_themes();
      return $themes[$id];
    }
  }
  
  /* Adds a color theme */
  
  function add_color_theme($name) {
    $id = preg_replace('/[^a-z0-9\-_ ]+/', '', strtolower($name));
    $id = preg_replace('/[ \-_]+/', '-', $id);
    $id = preg_replace('/(^-+|-+$)/', '', $id);
    if ($id) {
      $themes = $this->get_color_themes();
      if ($id === 'default' || array_key_exists($id, $themes)) {
        return array('error' => sprintf('A color theme with id "%s" already exists', $id));
      } else {
        $themes[$id] = esc_html($name);
        $this->update_option('color_themes', $themes);
        return array(
          'success' => true,
          'data' => array(
            'id' => $id,
            'name' => $themes[$id]
          )
        );
      }
    } else {
      return array('error' => sprintf('Invalid name: "%s"', $name));
    }
  }
  
  /* Renames a color theme */
  
  function rename_color_theme($id, $name) {
    $themes = $this->get_color_themes();
    if ($id === 'default') {
      return array('error' => "The default color theme can't be renamed.");
    } else if (array_key_exists($id, $themes)) {
      $response = $this->add_color_theme($name);
      if (isset($response['success']) && $response['success'] === true) {
        $new_id = $response['data']['id'];
        $current_data = $this->get_option('color-editor_' . $id);
        $this->update_option('color-editor_' . $new_id, $current_data);
        $stylesheet = $this->get_color_theme_stylesheet($id);
        if (file_exists($stylesheet)) @rename($stylesheet, $this->get_color_theme_stylesheet($new_id));
        $this->delete_color_theme($id);
        do_action(THEME_ID . '_rename_color_theme', $id, $response['data']['id']); // $old, $new
      }
      return $response;
    } else {
      return array('error' => sprintf('A color theme with id "%s" doesn\'t exist.', $id));
    }
  }
  
  /* Adds a color theme */
  
  function delete_color_theme($id) {
    $themes = $this->get_color_themes();
    if ($id === 'default') {
      return array('error' => "The default color theme can't be deleted.");
    } else if (array_key_exists($id, $themes)) {
      $out = array();
      foreach ($themes as $k => $v) if ($k !== $id) $out[$k] = $v;
      $this->update_option('color_themes', $out);
      $this->delete_option('color-editor_' . $id);
      $target = $this->get_color_theme_stylesheet($id);
      if (file_exists($target)) @unlink($target);
      return array('success' => true);
    } else {
      return array('error' => sprintf('A color theme with id "%s" doesn\'t exist.', $id));
    }
  }
  
  /* Flips a value */
  
  function flip_value($val) {
    switch($val) {
      case 'top': return 'bottom';
      case 'bottom': return 'top';
      case 'right': return 'left';
      case 'left': return 'right';
      case 'center': return 'center';
      case 'white': return 'black';
      case 'black': return 'white';
      case 1: case '1': return -1;
      case -1: case '-1': return 1;
      case 0: return 0;
      default: return null;
    }
  }
  
  /* Render nested layout */
  
  function render_nested_layout($layout, $args) {
    
    printf("\n".'<div class="component %s nested-layout clearfix">'."\n\n<!-- [NESTED LAYOUT] -->\n\n", $args['container_class']);
    
    $data = $this->layouts[$layout];
    
    foreach ($data as $container) {
      
      $this->layout->render_nested_container($container, $args['slots']);
      
    }
    
    echo "<!-- [/NESTED_LAYOUT] -->\n\n</div>";
    
  }
  
  /* Checks if a post has a layout assigned */
  
  function has_layout($id) {
    $layout = ( is_home() || is_front_page() || is_search() || is_archive() || is_404() ) ? null : $this->postmeta('layout');
    if (empty($layout) || !$this->layout_exists($layout)) $layout = $this->option($id);
    $layout_exists = ($layout && $this->layout_exists($layout));
    if ($layout_exists) $this->layout_to_render = $layout;
    return $layout_exists;
  }
  
  /* Renders the layout for a post or page */
  
  function render_layout() {
    if (is_singular() && post_password_required()) {
      echo '<br/>';
      $this->open_section();
      the_content();
      $this->close_section();
      echo '<br/>';
    } else {
      $layout = (!empty($this->layout_to_render)) ? $this->layout_to_render : $this->postmeta('layout');
      $this->layout_to_render = null;
      $this->layout($layout);
    }
  }
  
  /* Renders a message in a section */
  
  function message($str) {
    $this->open_section();
    echo '<div class="'. $this->layout->classes[LAYOUT_GRID_SIZE] .'">'."\n";
    echo apply_filters('__the_excerpt', $str);
    echo '</div>';
    $this->close_section();
  }
  
  /* Renders the metadata display */
  
  function metadata_display($setting) {
    switch ($setting) {
      case 'description':
        return $this->get_post_metadata('description');
      case 'author':
        return $this->get_post_metadata('author');
      case 'author-date':
        return $this->get_post_metadata('author-date');
      case 'date':
        return $this->get_post_metadata('date');
      case 'raw-date':
        return $this->get_post_metadata('raw-date');
      case 'comments':
        return $this->get_post_metadata('comments');
      case 'cats':
        return $this->get_post_metadata('categories');
      case 'tags':
        return $this->get_post_metadata('tags');
      case 'cats-tags':
        return $this->get_post_metadata('categories, tags');
      case 'author-date-cats':
        return $this->get_post_metadata('author-date, categories');
      case 'author-date-cats-tags':
        return $this->get_post_metadata('author-date, categories, tags');
      case 'author-cats':
        return $this->get_post_metadata('author, categories');
      case 'author-cats-tags':
        return $this->get_post_metadata('author, categories, tags');
      case 'query-details':
        if (is_category()) {
          return __("Category Archive", "theme");
        } elseif (is_tag()) {
          return __("Tag Archive", "theme");
        } else {
          return null;
        }
        break;
    }
  }
  
  /* Returns post metadata */
  
  function get_post_metadata($keys, $array=false) { global $post;
    
    $out = array();
    $post = get_post(get_the_ID());
    $post_type = $post->post_type;
    
    $post_format = get_post_format();
    if ($post_format === false) $post_format = 'standard';
    
    if (is_string($keys)) $keys = csv2array($keys);
    
    foreach ($keys as $key) {
      
      switch ($key) {
        
        case 'description':
          switch ($post_format) {
            case 'image':
              $description = $this->postmeta('image_description_str');
              break;
            case 'gallery':
              $description = $this->postmeta('gallery_description_str');
              break;
            default:
              $description = $this->postmeta('description');
              break;
          }
          $description = esc_html($description);
          if ($array) {
            $out['description'] = $description;
          } else {
            $out[] = $description;
          }
          break;
        
        case 'author':
        case 'author-date':
          $author = sprintf('<a rel="author" href="%s">%s</a>', 
            get_author_posts_url($post->post_author),
            get_the_author_meta('display_name', $post->post_author)
          );
          if ($key == 'author-date') {
            if ($array) {
              $out['author-date'] = array($author, get_the_date());
            } else {
              $out[] = sprintf(__('By %s on %s.', "theme"), $author, get_the_date());
            }
          } else if ($key == 'author') {
            if ($array) {
              $out['author'] = $author;
            } else {
              $out[] = sprintf(__('By %s.', "theme"), $author);
            }
          }
          break;
          
        case 'categories':
          // Assumed to be a taxonomy
          if ($post_type == 'post') {
            $taxonomy = 'category';
          } else {
            $taxonomies = get_object_taxonomies($post_type, 'objects');
            $taxonomy = array_shift(array_keys($taxonomies));
          }
          if ($taxonomy) {
            if ($array) {
              $terms = get_the_term_list($post->ID, $taxonomy, "", ",\n", "");
              if ($terms) $out['categories'] = $terms;
            } else {
              $terms = get_the_term_list($post->ID, $taxonomy, sprintf("%s ", __("Posted in", "theme")), ", ", '.');
              if ($terms) $out[] = $terms;
            }
          }
          break;
          
        case 'date':
          if ($array) {
            $out['date'] = get_the_date();
          } else {
            $date = sprintf(__('Published on %s.', "theme"), get_the_date());
            $out[] = $date;
          }
          break;

        case 'raw-date':
          if ($array) {
            $out['raw-date'] = get_the_date();
          } else {
            $out[] = get_the_date() . '.';
          }
          break;

        case 'tags':
          $taxonomy = ($post_type == 'post') ? 'post_tag' : null;
          if ($taxonomy) {
            if ($array) {
              $terms = get_the_term_list($post->ID, $taxonomy, "", ",\n", "");
              if ($terms) $out['tags'] = $terms;
            } else {
              $terms = get_the_term_list($post->ID, $taxonomy, sprintf("%s ", __("Tagged as", "theme")), ', ', '.');
              if ($terms) $out[] = $terms;
            }
          }
          break;
          
        case 'comments':
          if (comments_open()) {
            $count = get_comments_number();
            switch ($count) {
              case 0: $comments_str = (is_single()) ? __("No Comments", "theme") : __("Leave a Reply", "theme"); break;
              case 1: $comments_str = __("One Comment", "theme"); break;
              default: $comments_str = sprintf(__("%d Comments", "theme"), $count); break;
            }
            $comments_str = sprintf('<a href="%s%s">%s</a>', get_permalink(), "#comments", $comments_str);
            if ($array) {
              $out['comments'] = $comments_str;
            } else {
              $out[] = $comments_str;
            }
          }
          break;
    
      }

    }
    
    if ($array) {
      return $out;
    } else {
      $out = implode('&nbsp; ', $out);
      return preg_replace('/\.$/', '', $out);
    }
    
  }
  
  /* Renders a mustache template */
  
  function render($template, $vars) {
    return $this->mustache->render($template, $vars);
  }
  
  /* Renders a mustache template using its filename */
  
  function render_template($template, $vars) {
    if (array_key_exists($template, $this->template_cache)) {
      $source = $this->template_cache[$template];
    } else {
      $source = $this->template_cache[$template] = @file_get_contents(THEME_MUSTACHE_PATH . '/' . $template);
    }
    return $this->render($source, $vars);
  }
  
  /* Renders a layout */
  
  function layout($key) {
    $key = esc_html($key);
    $this->rendering_layout = true;
    $layout = $this->layouts[$key];
    foreach ($layout as $container) {
      $this->layout->render_container($container);
    }
    $this->rendering_layout = false;
  }
  
  /* Renders a layout for a specific option */
  
  function layout_for_option($id, $config_url=null) {
    
    $layout = $this->option($id);
    
    if ( $this->layout_exists($layout) ) {

      $this->layout($layout);
      
    } else if ($config_url && is_user_logged_in()) {
      
      echo '
<div style="width: 940px; margin: 0 auto; padding: 30px 0;">
' . __("This page has no layout assigned.", "theme") . '&nbsp; <a target="_blank" href="' . $config_url . '">' . __("Add one", "theme") . '</a>.
</div>';
    }

  }
  
  /* Checks if a layout exists */
  
  function layout_exists($layout) {
    return array_key_exists(esc_html($layout), $this->layouts);
  }
  
  /* Opens a section */
  
  function open_section() {
    echo $this->layout->open_container;
  }
  
  /* Adds a separator between containers */
  
  function middle() {
    $this->close_section();
    $this->open_section();
  }
  
  /* Close session */
  
  function close_section() {
    echo $this->layout->close_container;
  }

  /* Returns an option from a context */
  
  function context_option($context, $id, $default=null) {
    $this->set_context($context);
    $out = $this->option($id, $default);
    $this->restore_context();
    return $out;
  }
  
  /* Returns options from a context */
  
  function context_options($context, $opts) {
    $this->set_context($context);
    $out = $this->options($opts);
    $this->restore_context();
    return $out;
  }

  /* Returns a theme option */
  
  function option($id, $default=null) {
    
    $options = array_key_exists($this->options_context, $this->options) ? $this->options[$this->options_context] : null;
    
    if (is_array($options)) {
      
      $out = isset($options[$id]) ? $options[$id] : null;

      if (is_null($out)) {
        if ($default) {
          return $default;
        } else {
          if (isset($this->default_options[$this->options_context][$id])) {
            return $this->default_options[$this->options_context][$id];
          } else {
            return null;
          }
        }
        
      } else {
        return (is_string($out)) ? stripslashes($out) : $out;
      }
      
    } else {
      
      // Retrieve options for context
      $this->load_options($this->options_context);
      
      // Recursively run the method, this time with the options set
      return $this->option($id, $default);
      
    }

  }
  
  /* Returns multiple options */
  
  function options($opts) {
    $options = (is_array($opts)) ? $opts : csv2array($opts);
    $out = array();
    foreach ($options as $key) {
      $out[$key] = $this->option($key);
    }
    return $out;
  }
  
  /* Returns a theme option, making sure it's a boolean */
  
  function option_bool($id, $default=null) {
    $out = $this->option($id, $default);
    if (is_string($out)) {
      switch(strtolower($out)) {
        case 'yes':
        case 'enable':
        case 'enabled':
        case 'show':
          return true;
        default:
          return false;
      }
    } else if (is_bool($out)) {
      return $out;
    } else {
      return false;
    }
  }
  
  private function load_options($context) { global $theme_options;
    
    if ( ! is_admin() ) {
      // Backup current theme options (if any)
      $backup_theme_options = $theme_options;
      $theme_options = array();

      // Retrieve defaults for context
      $defaults = array();
      $path = $this->path(sprintf('includes/options/%s.php', $context));
      
      include_once($path);

      foreach ($theme_options as $section => $options) {
        foreach ($options as $title => $args) {
          if (isset($args['id']) && isset($args['default'])) {
            $defaults[$args['id']] = $args['default'];
          }
        }
      }
      
      $this->default_options[$context] = $defaults;
      
      // Restore current theme options
      $theme_options = $backup_theme_options;
    }
    
    ////////////// DO NOT DELETE
    
    // Retrieve options for context
    $this->options[$this->options_context] = apply_filters('der_load_options', $this->get_option_array($this->options_context), $context);
    
    ////////////// DO NOT DELETE
    
  }
  
  /* Sets an option context */
  
  function set_context($context) {
    $this->options_context_last = $this->options_context;
    $this->options_context = apply_filters('theme_options_context', $context);
  }
  
  /* Gets the current options context */
  
  function get_context() {
    return $this->options_context;
  }
  
  /* Sets the default option context */
  
  function default_context() {
    $this->set_context('theme-options');
  }
  
  /* Resets the theme options context */
  
  function reset_context() {
    $this->default_context();
  }
  
  /* Restores the previous context */
  
  function restore_context() {
    $this->options_context = $this->options_context_last;
  }
  
  /* Returns the context key, used to save multiple options */
  
  function context_key() {
    return $this->key($this->options_context);
  }
 
  /* Returns a WordPress option */
  
  function get_option($id) {
    $val = get_option($this->key($id));
    if ($val && is_string($val)) {
      // Replace invisible chars with spaces
      $val = str_replace(chr(226), ' ', $val);
    }
    return $val;
  }
  
  /* Returns an option, ensuring an array return type */
  
  function get_option_array($id) {
    $opt = $this->get_option($id);
    if (empty($opt)) return array();
    else if (is_array($opt)) return $opt;
    else return unserialize($opt);
  }
  
  /* Updates a worpdress WordPress option */
  
  function update_option($id, $val) {
    return update_option($this->key($id), $val);
  }
  
  /* Removes a WordPress option */
  
  function delete_option($id) {
    return delete_option($this->key($id));
  }
  
  /* Returns a key prefixed with the theme id */
  
  function key($key) {
    return $this->prefix . strtolower($key);
  }
  
  /* Returns a theme uri */
  
  function uri($path='', $ver='') {
    if ($ver) $ver = (preg_match('/\?/', $path) ? '&' : '?') . "ver=${ver}";
    $uri = $this->template_directory_uri . ($path ? '/' . $path . $ver : '');
    return apply_filters('theme_uri', $uri, $path, $ver);
  }
  
  /* Returns a theme path */
  
  function path($path) {
    return get_template_directory() . '/' . $path;
  }
  
  /* Enables option pages */

  function option_pages($arr) { global $theme_admin_menus;
    $theme_admin_menus = $arr;
  }
    
  /* Theme Nonce */
  
  function nonce() {
    wp_nonce_field(THEME_NONCE_VERIFY, THEME_NONCE); echo "\n";
  }
  
  /* Checks if a nonce is valid */
  
  function verify_nonce() {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'GET') {
      return ( isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) );
    } else if ($method == 'POST') {
      return ( isset($_POST[THEME_NONCE]) AND wp_verify_nonce($_POST[THEME_NONCE], THEME_NONCE_VERIFY) );
    }
  }
  
  /* 
    Returns a meta option from the active post in the loop.
    This method takes into account the meta prefix.
  */
  
  function postmeta($key, $multiple=false) {
    
    $post_id = get_the_ID();

    return $this->get_postmeta($post_id, $key, $multiple);
    
  }
  
  /* Returns a prefixed meta key */
  
  /* 
    Wrapper for get_post_meta. Returns a meta key from a Post ID. 
    This method takes into account the meta prefix.
  */
  
  function get_postmeta($post_id, $key, $multiple=false) {
    
    // Process multiple values (checkboxes)
    if ($multiple) {
      
      $out = array();
      $data = get_post_custom($post_id);
      $regex = sprintf("/^%s:%s:_/", $this->prefix, $key);
      
      foreach ($data as $k => $v) {
        if (preg_match($regex, $k)) {
          $val = preg_replace($regex, '', $k);
          $out[] = (is_numeric($val)) ? (int) $val : stripslashes($val);
        }
      }
      
      return $out;
      
    // Process regular postmeta key
    } else {
      
      $key = $this->key($key);
      
      return stripslashes(get_post_meta($post_id, $key, true));
      
    }

  }
  
  /* Returns a meta value as a boolean */
  
  function postmeta_bool($key) {
    $val = $this->postmeta($key);
    switch (strtolower($val)) {
      case 'yes':
      case 'enable':
      case 'enabled':
        return true;
      default:
        return false;
    }
  }
  
  /* Gets taxonomies */
  
  function get_taxonomies($taxonomy, $slug=true) {
    $taxes = get_terms($taxonomy, array('hide_empty'=> false));
    $values = array();
    foreach ($taxes as $tax) {
      $values[ ($slug) ? $tax->slug : $tax->term_id ] = esc_html($tax->name);
    }
    return $values;
  }
  
  /* Retrieves the featured image */
  
  function post_image($id=null) {
    $post_id = $id ? $id : get_the_ID();
    $thumb_id = get_post_thumbnail_id($post_id);
    $data = wp_get_attachment_image_src($thumb_id, '', false);
    if ($data) {
      $img = $data[0];
      return $img;
    } else {
      return null;
    }
  }
  
  /* Generates a thumbnail URL */
  
  function thumb_src($img, $w, $h=0, $align='') {
    
    // Process align query var
    if (preg_match('/^[^\?]+\?a=[a-z]{1,2}$/', $img)) {
      $img = explode('?a=', $img);
      list($img, $align) = $img;
    }
    
    // Remove default if set
    if ($align == 'c') $align = null;
    
    return mr_image_resize($img, $w, $h, true, $align, false);

  }
  
  /* Generates a featured image thumb */
  
  function post_thumb($w, $h=0, $id=null) {
    $img = $this->post_image($id);
    if ($img) {
      $align = $this->postmeta(THUMB_CROP_META);
      return $this->thumb_src($img, $w, $h, $align);
    } else {
      return null;
    }
  }
  
  /* Retrieves a list of files from a theme directory */
  
  function get_files($dir, $ext='', $fullpath=false) {
    $path = get_template_directory() . '/' . $dir;
    $out = array();
    $files = scandir($path);
    foreach ($files as $file) {
      if ( $file == '.' OR $file == '..' OR $file[0] == '.' ) continue;
      if ( !empty($ext) ) {
        $ext = preg_replace('/\\./', '\\.', $ext);
        $regex = sprintf('/\.%s$/', $ext);
        if (preg_match($regex, $file)) {
          $out[] = ($fullpath) ? $path . '/' . $file : $file;
        }
      } else {
        $out[] = ($fullpath) ? $path . '/' . $file : $file;
      }
    }
    return $out;
  }
  
  /* Gets the site's domain */
  
  function get_hostname() {
    $parts = parse_url($this->home_url);
    return $parts['host'];
  }
  
  /* Sets pagination defaults */
  
  function set_pagination_defaults($args) {
    $this->pagination_defaults = wp_parse_args($args, $this->pagination_defaults);
  }
  
  /* Paginates a query.*/
  
  function paginate($query=null, $echo=false) {//global $paged;
    
    // Uses code from http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
    
    if (empty($query)) $query = $GLOBALS['wp_query'];
   
    $options = $this->pagination_defaults;
 
    $html = '';
    $showitems = ($options["range"] * 2) + 1;

    $paged = defined('__PAGED__') ? __PAGED__ : get_query_var('paged');

    if (empty($paged)) $paged = 1;

    $pages = $query->max_num_pages;
    
    if ($pages == 1) return;
    
    if ($pages != -1) {
      
      if ($options["nav"] && $paged > 1 && $showitems < $pages) {
        if ($paged >= ($options['range'] + 2)) {
          $html .= "\n" . sprintf('  <li class="%s %s"><a title="' . sprintf(__("Page %d", "theme"), 1) .'" href="', $options["nav_class"], $options["rewind_class"]) . get_pagenum_link(1) . '"></a></li>';
        } else {
          $html .= "\n" . sprintf('  <li class="%s %s"><a href="', $options["nav_class"], $options["prev_class"]) . get_pagenum_link($paged - 1) . '">' . $options["prev"] . '</a></li>';
        }
      }

      $start = $paged - $options['range'];
      if ($start <= 0) $start = 1;
      
      $limit = $start + 2*$options['range'];
      
      if ($limit > $pages) {
        $start -= ($limit - $pages);
        if ($start <= 0) $start = 1;
        $limit -= ($limit - $pages);
      }

      for ($i=$start; $i <= $limit; $i++) {
        // if ($pages != -1 && ( ! ($i >= $paged+$options["range"]+1 || $i <= $paged-$options["range"]-1) || $pages <= $showitems )) {
          if ($paged == $i) $active_page = $i;
          $html .= ($paged == $i) 
          ? "\n" . '  <li class="' . sprintf('%s %s', $options["active_class"], $options["page_class"]) . '">' . $i . '</li>'
          : "\n" . '  <li class="' . $options["page_class"] . '"><a href="' . get_pagenum_link($i) . '">' . $i .'</a></li>';
        // }
      }

      if ($options["nav"] && $paged < $pages && $showitems < $pages) {
        if ($paged <= ($pages - $options['range'] - 1)) {
          $html .= "\n" . sprintf('  <li class="%s %s"><a title="' . sprintf(__("Page %d", "theme"), $pages) . '" href="', $options["nav_class"], $options["fast_forward_class"]) . get_pagenum_link($pages) . '"></a></li>';
        } else {
          $html .= "\n" . sprintf('  <li class="%s %s"><a href="', $options["nav_class"], $options["next_class"]) . get_pagenum_link($paged + 1) . '">' . $options["next"] . '</a></li>';
        }
      }
      
      $html .= sprintf("\n</ul>\n%s", $options["container_close"]);
    }
    
    $html = sprintf("%s\n<ul class=\"%s\">", $options["container_open"], $options["ul_class"]) . $html;
    
    if ($echo) echo $html;
    else return $html;

  }

  /* Registers a custom post type. Serves as a wrapper for 'register_post_type' */
  
  function add_post_type($opts) {

    // Set $args array if not provided
    $opts = wp_parse_args($opts, array(
      'slug' => null,
      'singular_name' => null,
      'plural_name' => null,
      'args' => array()
    ));
    
    // Provides: $slug, $singular_name, $plural_name, $args
    extract($opts);
    
  	$defaults = array(
  		'public' => true,
  		'menu_position' => 50,
  		'supports' => 'title, editor, excerpt, trackbacks, comments, revisions'
  	);

  	$args = wp_parse_args($args, $defaults);

    // Convert 'suppports' string to an array
  	$args['supports'] = csv2array($args['supports']);
  	
  	$labels = isset($args['labels']) ? $args['labels'] : null;
  	
  	// Set labels
  	$args['labels'] = array(
			'name'	=>	$plural_name,
			'singular_name'	=>	$singular_name,
			'add_new'	=>	'Add New',
			'add_new_item' => 'Add New' . " ${singular_name}",
			'edit_item' => 'Edit' . " ${singular_name}",
			'new_item' => 'New' . " ${singular_name}",
			'view_item' => 'View' . " ${singular_name}",
			'search_items' => 'Search' . " ${plural_name}",
			'not_found' => sprintf('No %s found', $plural_name),
			'not_found_in_trash' => sprintf('No %s found in Trash', $plural_name),
		);

		$args['labels'] = wp_parse_args($labels, $args['labels']);
		
  	register_post_type($slug, $args);
  	
  	// If there's a column callback, run it
  	if (!empty($columns_callback) && function_exists($columns_callback)) {
  	  $this->manage_columns($slug, $columns_callback, (isset($args['hierarchical']) ? $args['hierarchical'] : null) );
  	}

  }
  
  /* Manages Post/Page Columns */
  
  function manage_columns($post_type, $callback, $hierarchical=false) {
    if (is_admin()) {
      $context = ($hierarchical) ? 'pages' : 'posts';
      add_action("manage_${context}_custom_column", $callback, 10, 2);
      add_filter("manage_{$post_type}_posts_columns", $callback);
    }
  }

  /* Registers a taxonomy for an object type */
  
  function add_taxonomy($opts) {
    
    // Set default values array if not provided
    $opts = wp_parse_args($opts, array(
      'slug' => null,
      'post_type' => null,
      'singular_name' => null,
      'plural_name' => null,
      'args' => array(),
    ));
    
    extract($opts);
    
    $defaults = array(
      'labels' => array(
  			'name'	=>	$plural_name,
  			'singular_name' => $singular_name,
  			'search_items' => 'Search' . ' ' . $plural_name, 
  			'all_items' => 'All' . ' ' . $plural_name,
  			'parent_item' => 'Parent' . ' ' . $singular_name,
  			'parent_item_colon' => 'Parent' . ' ' . $singular_name . ':',
  			'edit item' => 'Edit' . ' ' . $singular_name,
  			'update_item' => 'Update' . ' ' . $singular_name,
  			'add_new_item' => 'Add New' . ' ' . $singular_name,
  			'new_item_name' => 'New' . ' ' . (isset($post_type_name) ? $post_type_name : null) . $singular_name . ' ' . 'Name'
      )
    );
    
    $args = wp_parse_args($args, $defaults);
    
    register_taxonomy($slug, $post_type, $args); // If $post_type is null, it will be ignored
    
  }

  /* Adds Sidebars */
  
  function add_sidebar($args) { global $theme_sidebars;
    // Calling register sidebars multiple times is more effective than calling 'register_sidebars()'
    // http://codex.wordpress.org/Function_Reference/register_sidebar#Notes
    
    $args = wp_parse_args($args, $this->widget_defaults);
    
    // Set class default to be the same id
    if (empty($args['class']) && isset($args['id']))  $args['class'] = $args['id'];
    
    // Enqueue sidebar definition (sidebars are registered on the 'widgets_init' event)
    $theme_sidebars[] = $args;
  }
  
  /* Sets widget defaults, to be used by '$der_framework->add_sidebar' */
  
  function set_widget_defaults($args) {
    $this->widget_defaults = wp_parse_args($args, $this->widget_defaults);
  }
  
  /* Renders widgets from specific sidebar id's */
  
  function widgets() {
    $args = func_get_args();
    $len = count($args);
    $limit = $len - 1;
    for ($i=0; $i < $len; $i++) {
      $sidebar = $args[$i];
      if ($i==0) echo "\n<!-- [${sidebar}] -->\n";
      dynamic_sidebar($sidebar);
      if ($i < $limit) {
        $next_sidebar = $args[$i+1];
        echo "\n<!-- [/${sidebar}] | [${next_sidebar}] -->\n";
      } else {
        echo "\n<!-- [/${sidebar}] -->\n";
      }
    }
    echo "\n"; // prettify
  }
  
  /* Renders a navigation menu */
  
  function menu($location) {
    $args = array(
      'theme_location' => $location,
      'container' => false,
      'items_wrap' => '%3$s'
    );
    $args['echo'] = false;
    $out = wp_nav_menu($args);
    $out = preg_replace('/^<div class="menu"><ul>|<\\/ul><\\/div>$/', '', $out);
    $out = str_replace('current_page_item', 'current_page_item current-menu-item', $out);
    return $out;
  }
  
  /* Better excerpt formatting */
  
  function excerpt() {
    $post = get_post(get_the_ID());
    $more_tag = preg_match($this->more_tag, $post->post_content);
    if ($more_tag) {
      $content = explode($this->more_tag, $post->post_content);
      $content = $this->content($content[0], true);
    	return trim($content);
    } else {
      
      /* 
        When calling get_the_excerpt(), it applies the 'the_content' filter [1]
        after retrieving the excerpt on the 'wp_trim_excerpt' filter.
        
        Some plugins add filters to the 'the_content' filter, which will result in
        such content appearing on excerpts on some situations.
        
        The code below will make sure that the theme's internal '__the_content' and
        '__the_excerpt' filters (which are the default filters applied by WordPress,
        before any plugins) are used [2].
        
        Since WordPress runs synchronously, the 'the_content' filter is temporarily
        replaced with the theme's internal '__the_content', to make sure only the 
        default content filters are applied, without any ones added by plugins.
        
        After the excerpt is generated, the 'the_content' filter is then restored.
      
        [1] https://github.com/WordPress/WordPress/blob/3.5-branch/wp-includes/formatting.php#L2150
        [2] https://github.com/WordPress/WordPress/blob/3.5-branch/wp-includes/default-filters.php#L135
       */

      global $wp_filter;
      $original = $wp_filter['the_content']; // Backup original 'the_content' filter
      $wp_filter['the_content'] = $wp_filter['__the_content']; // Replace filter temporarily
      $out = apply_filters('__the_excerpt', get_the_excerpt()); // Get excerpt (applies 'the_content' filter) internally
      $wp_filter['the_content'] = $original; // Restore filter
      return trim($out);
    }
  }
  
  /* Prepare content */
  
  function content($content, $shortcodes=true) {
    if ($shortcodes) {
      $content = apply_filters('the_content', $content);
    } else {
      $content = strip_shortcodes($content);
      $content = apply_filters('__the_content', $content);
    }
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = apply_filters('der_content', $content);
  	return trim($content);
  }
  
  /* Do shortcodes */
  
  function shortcode($content) {
    return apply_filters('der_shortcode', do_shortcode($content));
  }
  
  /* Encrypts a string securely */
  
  function encrypt($string) {
    // Note: NONCE_SALT is unique for each wp installation
    $salt = md5(NONCE_SALT);
    $mcrypt_iv = md5(sha1($salt));
    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $string, MCRYPT_MODE_CBC, $mcrypt_iv));
    return $encrypted;
  }
  
  /* Encrypts an array */
  
  function encrypt_array($arr) {
    return $this->encrypt(serialize($arr));
  }
  
  /* Decrypts a string securely */
  
  function decrypt($hash) {
    // Note: NONCE_SALT is unique for each wp installation
    $salt = md5(NONCE_SALT);
    $mcrypt_iv = md5(sha1($salt));
    $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($hash), MCRYPT_MODE_CBC, $mcrypt_iv), "\0");
    return $decrypted;
  }
  
  /* Decrypts an array */
  
  function decrypt_array($hash) {
    return unserialize($this->decrypt($hash));
  }
  
  /* Configures the theme's sidebars */

  function sidebar_config($config) {
    $sidebars = list2array($this->option('sidebars', THEME_DEFAULT_SIDEBAR));
    $sidebars = array_merge($sidebars, list2array(THEME_FOOTER_SIDEBARS));
    foreach ($sidebars as $sidebar) {
      $sidebar = array_merge(array(
        'id' => id_from_str($sidebar),
        'name' => $sidebar,
      ), $config);
      $this->add_sidebar($sidebar);
    }
  }
  
  /* Random Junk */
  
  function rand_str() {
    if (empty($this->rand)) {
      $rand = 'L)8247md6.,7*:5!}././yp$w+kNc@i=_=R2r4^K=8Fu{d403(1E&;k{5~,Vd8{h8n52MP)8}2Gf3~&1^w~|=O/igY%8Xb70';
      $this->rand = str_shuffle($rand . uniqid());
    }
    return $this->rand;
  }
  
  /* Get adjacent post links */
  
  function adjacent_post_links() {
    
    // http://core.trac.wordpress.org/browser/branches/3.5/wp-includes/link-template.php#L1364
    
    if (is_attachment()) {
      $prev_post = get_post(get_post()->post_parent);
    } else {
      $prev_post = get_adjacent_post(false, '', true);
    }
    $next_post = get_adjacent_post(false, '', false);
    return array(
      'prev' => ($prev_post) ? get_permalink($prev_post->ID) : null,
      'prev_title' => ($prev_post) ? get_the_title($prev_post->ID) : null,
      'next' => ($next_post) ? get_permalink($next_post->ID) : null,
      'next_title' => ($next_post) ? get_the_title($next_post->ID) : null
    );
  }
  
  /* Loads a layout component from 'includes/components' */

  private function load_files($str, $location) {
    $arr = (is_array($str)) ? $str : csv2array($str);
    foreach ($arr as $handle) {
      require($this->path(sprintf("%s/%s.php", $location, $handle)));
    }
  }
  
  /* Loads post types from 'includes/post-types' */
  
  function load_post_types($str) {
    $this->load_files($str, "includes/post-types");
  }
  
  /* Loads widgets from 'includes/widgets' */
  
  function load_widgets($str) {
    $this->load_files($str, "includes/widgets");
  }
  
  /* Loads bundles from 'includes/bundles' */
  
  function load_bundles($str) {
    $this->load_files($str, "includes/bundles");
  }
  
  /* Loads metaboxes from 'includes/metaboxes' */
  
  function load_metaboxes($str) {
    $this->load_files($str, "includes/metaboxes");
  }
  
  /* Loads layout components from 'includes/components' */
  
  function load_components($str) {
    $this->load_files($str, "includes/components");
  }
  
  /* Loads shortcodes from 'includes/shortcodes' */
  
  function load_shortcodes($str) {
    $this->load_files($str, "includes/shortcodes");
  }
  
  /* Loads widgets from 'includes/functions' */
  
  function load_functions($str) {
    $this->load_files($str, "includes/functions");
  }
  
}

?>
