<?php

  /* Theme Functions */
  
  global $der_framework;
  
  add_filter('der_content', 'theme_mini_shortcode');
  add_filter('der_shortcode', 'theme_mini_shortcode');
  add_filter('excerpt_length', 'theme_excerpt_length');
  add_filter('excerpt_more', 'theme_excerpt_more');
  add_filter('embed_oembed_html', 'theme_embed_output', 9999, 3);
  add_filter('the_title', 'esc_html', 9999);
  
  add_action('wp_head', 'meteor_custom_stylesheet');
  add_action('wp_footer', 'meteor_footer_code', 9999);
  
  $der_framework->set_pagination_defaults(array(
    'container_open' => '<div class="meteor-pagination tooltips">',
    'container_close' => '</div><!-- .meteor-pagination -->',
    'nav_jump' => false
  ));
  
  $der_framework->load_components(array(
    'layout-in-layout',
    'meteor-404',
    'meteor-aside-posts',
    'meteor-blog-posts',
    'meteor-content',
    'meteor-cta',
    'meteor-google-map',
    'meteor-icon-posts',
    'meteor-logo-display',
    'meteor-pagination',
    'meteor-posts',
    'meteor-pricing',
    'meteor-section-title',
    'meteor-sidebar',
    'meteor-single-post',
    'meteor-slider-bundle',
    'meteor-slider',
    'php-code-component'
  ));
  
  $der_framework->load_functions(array(
    'component-get-posts',
    'theme-default-content',
    'theme-development',
    'theme-get-gallery-images',
    'theme-metadata-html'
  ));

  $der_framework->load_shortcodes(array(
    'buttons',
    'columns',
    'form',
    'icon-lists',
    'meteor-gallery',
    'meteor-icon',
    'notifications',
    'portfolio-gallery',
    'shortcodes',
    'testimonials',
    'typography'
  ));
  
  //////////////////////////// FUNCTIONS
  
  function meteor_footer_code() { global $der_framework;
    $footer_template = $der_framework->path('includes/theme-footer.php');
    include($footer_template);
  }
  
  function meteor_custom_stylesheet() { global $der_framework;
     printf("\n<link rel='stylesheet' id='meteor-custom-css' href='%s' type='text/css' media='all' />\n", $der_framework->uri('core/css/meteor.custom.css', THEME_VERSION));
  }
  
  function theme_embed_output( $html, $url, $attr ) {
    $ignore = array(); // Added just in case it's needed later
    foreach ($ignore as $domain) if (strstr($url, $domain)) return $html;
    $html = theme_customize_video_embed($html);
    return sprintf('<p class="embed-container">%s</p>', $html);
  }
  
  function theme_customize_video_embed($content) { global $der_framework;
    $src_pattern = '/src="([^"]+)"/';
    if (preg_match('/youtube\.com/', $content)) {
      preg_match($src_pattern, $content, $matches);
      if ($matches) {
        $url = $matches[1];
        $params = preg_match('/\?/', $url) ? '&' : '?';
        $params .= esc_html('HD=1&rel=0&showinfo=0&autohide=1');
        $content = str_replace($url, $url . $params, $content);
      }
    } else if (preg_match('/vimeo\.com/', $content)) {
      preg_match($src_pattern, $content, $matches);
      if ($matches) {
        $url = $matches[1];
        $color = str_replace('#', '', $der_framework->color_theme_option('accent', '#fa5b15'));
        $params = preg_match('/\?/', $url) ? '&' : '?';
        $params .= sprintf(esc_html('byline=0&portrait=0&title=0&color=%s'), $color);
        $content = str_replace($url, $url . $params, $content);
      }
      // pre_html($content);
    }
    return $content;
  }
  
  
  function theme_set_thumb_aspect_ratio(&$args) {
    switch ($args['thumb_aspect_ratio']) {
      case 'thumb_height':
        $args['height'] = (int) $args['thumb_height'];
        if ($args['height'] === 0) {
          $args['height'] = floor($args['width']*GOLDEN_RATIO_FACTOR);
        }
        break;
      case 'golden_ratio':
        $args['height'] = floor($args['width']*GOLDEN_RATIO_FACTOR);
        break;
      case 'original':
        $args['height'] = 0;
        break;
    }
  }
  
  function theme_set_thumb_options(&$args) {
    // Thumb options
    $thumb_options = $args['thumb_options'];
    $args['opt_permalink'] = in_array('permalink', $thumb_options);
    $args['opt_lightbox'] = in_array('lightbox', $thumb_options);
    $args['opt_gallery'] = in_array('gallery', $thumb_options);
  }
  
  function theme_mini_shortcode($str) {
    $str = preg_replace('/\[icon-([^ \]]+)\]/', '<i class="icon-$1"></i>', $str);
    $str = preg_replace('/\[space\]/', ' &nbsp; ', $str);
    return $str;
  }
  
  function theme_excerpt_length() {
    return 55;
  }
  
  function theme_excerpt_more() { global $excerpt_length;
    return '&hellip;';
  }
  
  function theme_header_search() { global $der_framework;
    $options = array();
    $behaviour = $der_framework->option('header_search_behavior', 'default');
    if ($behaviour == 'autosearch') $options[] = 'autosearch';
    return '<li class="search hoverable"'. theme_options_attr(" data-options", $options) .'>
  <form class="clearfix" action="'. home_url() .'" method="get">
    <p><input type="text" name="s" placeholder="'. __("Search", "theme") .'" autocomplete="off" /></p> 
  </form>
</li>'."\n";
  }
  
  function theme_options_attr($attr, $options) {
    if (empty($options)) {
      return '';
    } else {
      $out = array();
      foreach ($options as $key => $val) {
        if (is_int($key)) {
          $out[] = $val;
        } else if (is_string($key) && $val === true) {
          $out[] = $key;
        } else {
          if (is_string($val)) $val = trim($val);
          if ($val === false || is_null($val)) $val = 'false';
          $out[] = sprintf("%s:%s", $key, $val);
        }
      }
      return sprintf('%s="%s"', $attr, implode($out, ', '));
    }
  }
  
?>