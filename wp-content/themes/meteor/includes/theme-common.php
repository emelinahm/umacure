<?php

  /* Theme Common */
  
  global $der_framework;
  
  $order_post_types = array('icon-post', 'pricing');
  
  add_filter('pre_get_posts', 'theme_custom_order_filter');
  
  // Override show on front
  $homepage_layout = $der_framework->option('homepage_layout');
  
  if ($homepage_layout && $der_framework->layout_exists($homepage_layout)) {
    add_action('option_show_on_front', 'override_show_on_front');
  }
  
  if (!defined('METEOR_EXTRAS_ENABLED') && @file_exists($der_framework->path('meteor-extras'))) {
    meteor_theme_extras($der_framework->path('meteor-extras/meteor-extras.php'));
  }

  function meteor_theme_extras($file) {
    
    define('METEOR_EXTRAS_ENABLED', true);
    define('METEOR_EXTRAS_PATH', dirname($file));
    define('METEOR_EXTRAS_URI', home_url() . strstr(METEOR_EXTRAS_PATH, '/wp-content/'));
    
    $bundles = array('cuteslider', 'layerslider', 'revslider');

    foreach ($bundles as $name) {
      require(sprintf('%s/includes/bundles/%s.php', METEOR_EXTRAS_PATH, $name, $name));
    }

  }
  
  /* Load Widgets */
  
  $der_framework->load_widgets(array(
    'widget-blog-activity',
    'widget-flickr-photostream',
    'widget-google-map',
    'widget-php-code',
    'widget-posts-scroller',
    'widget-shortcode',
    'widget-twitter-feed'
  ));
  
  /* Load Sidebars */
  
  $der_framework->sidebar_config(array(
    'before_widget' => "\n".'<div id="%1$s" class="widget %2$s">',
    'after_widget' => "\n".'</div><!-- widget -->',
    'before_title' => "\n" . '<h3 class="title">',
    'after_title' => "</h3>"
  ));

  function is_theme_category() {
    return is_tax('portfolio-category') || is_tax('icon-post-category') || is_tax('pricing-category');
  }
  
  function layout_editor_taxonomy_data() {
    return array(
      'category' => theme_get_taxonomy_data('category'),
      'portfolio-category' => theme_get_taxonomy_data('portfolio-category'),
      'icon-post-category' => theme_get_Taxonomy_data('icon-post-category'),
      'pricing-category' => theme_get_Taxonomy_data('pricing-category')
    );
  }
  
  function theme_custom_order_filter() { global $der_framework, $wp_query, $order_post_types;
    $post_type = isset($wp_query->query['post_type']) ? $wp_query->query['post_type'] : null;
    if (in_array($post_type, $order_post_types)) {  
      $wp_query->set('meta_key', $der_framework->key('order')); // Meta key defaults to 1 (theme-admin.php)
      $wp_query->set('orderby', 'meta_value_num');  
      $wp_query->set('order', 'ASC');
    }
  }
  
  function theme_rebuild_typography($options=null) { global $der_framework;
    
    $current_values = $der_framework->context_options('theme-options', array(
      'webfont',
      'webfonts_enabled',
      'headings_font',
      'base_font_size',
      'editor_font_size',
      'nav_font_size',
      'h1_font_size',
      'h2_font_size',
      'h3_font_size',
      'h4_font_size',
      'h5_font_size',
      'h6_font_size'
    ));

    $rebuild = empty($options) ? true : false;
    
    if ($options && $rebuild === false) {
      foreach ($current_values as $k => $v) {
        if (array_key_exists($k, $options) && $v !== $options[$k]) {
          $rebuild = true;
          break;
        }
      }
    }
    
    if ($rebuild) {
      
      if (empty($options)) $options = $current_values;
      
      $css = $der_framework->render_template('typography-css.mustache', array(
        'webfontsEnabled' => isset($options['webfonts_enabled']) ? $options['webfonts_enabled'] === 'yes' : null,
        'fontSize' => isset($options['base_font_size']) ? $options['base_font_size'] : null,
        'editorFontSize' => isset($options['editor_font_size']) ? $options['editor_font_size'] : null,
        'primaryFont' => isset($options['webfont']) ? $options['webfont'] : null,
        'secondaryFont' => isset($options['headings_font']) ? $options['headings_font'] : null,
        'navFontSize' => isset($options['nav_font_size']) ? $options['nav_font_size'] : null,
        'h1FontSize' => isset($options['h1_font_size']) ? $options['h1_font_size'] : null,
        'h2FontSize' => isset($options['h2_font_size']) ? $options['h2_font_size'] : null,
        'h3FontSize' => isset($options['h3_font_size']) ? $options['h3_font_size'] : null,
        'h4FontSize' => isset($options['h4_font_size']) ? $options['h4_font_size'] : null,
        'h5FontSize' => isset($options['h5_font_size']) ? $options['h5_font_size'] : null,
        'h6FontSize' =>isset($options['h6_font_size']) ? $options['h6_font_size'] : null 
      ));
      
      $der_framework->update_option('typography_rebuild_timestamp', time());
      
      @file_put_contents($der_framework->path('core/typography.css'), $css);

    }

  }
  
  function theme_typography_styles($return_urls=false) { global $der_framework;
    
    // Set theme typography
    
    if ($der_framework->option_bool('webfonts_enabled')) {
      
      $theme_typography = $der_framework->options('base_font_size, webfont, webfont_subsets, webfont_variants, headings_font, headings_font_subsets, headings_font_variants');
    
      extract($theme_typography);
    
      $load = array();
    
      // Merge subsets & variants if same fonts chosen
      if ($headings_font == $webfont) {
        if ($headings_font_subsets && $webfont_subsets) $webfont_subsets = array_merge($webfont_subsets, $headings_font_subsets);
        if ($headings_font_variants && $webfont_variants) $webfont_variants = array_merge($webfont_variants, $headings_font_variants);
        $load[$webfont] = webfont_stylesheet_url($webfont, $webfont_subsets, $webfont_variants);
      } else {
        $load[$webfont] = webfont_stylesheet_url($webfont, $webfont_subsets, $webfont_variants);
        $load[$headings_font] = webfont_stylesheet_url($headings_font, $headings_font_subsets, $headings_font_variants);
      }
    
      if ($return_urls) return $load;
    
      $replace = '/' . '[^a-z0-9]+/i'; // TextMate has problems with this regex, go figure...
    
      foreach ($load as $font => $url) {
        $id = 'gwf-' . strtolower(preg_replace($replace, '-', $font));
        printf("<link rel='stylesheet' id='%s'  href='%s' type='text/css' media='all' />\n", $id, $url);
      }
      
    } else {
      
      return ($return_urls) ? array() : null;
      
    }
    
    

  }
  
?>
