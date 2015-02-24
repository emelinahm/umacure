<?php

  function meteor_section_title($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'show_description' => true,
      'display_as_desc' => 'description',
      'display_options' => array(),
      'show_breadcrumb' => 'theme_default'
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    if ($args['show_breadcrumb'] === 'theme_default') {
      $args['show_breadcrumb'] = $der_framework->option_bool('show_breadcrumb');
    }
    
    if ($args['show_breadcrumb'] === true) {
      $args['breadcrumb'] = theme_breadcrumbs();
    }
    
    if (is_home()) {
      $args['title'] = get_bloginfo('name');
      $args['description'] = get_bloginfo('description');
    } else if (is_archive()) {
      $args['title'] = wp_title(null, false);
      if (is_author()) {
        $args['description'] = __("Author Archive", "theme");
      } else if (is_tag()) {
        $args['description'] = __("Tag Archive", "theme");
      } else if (is_day() || is_year() || is_month()) {
        $args['description'] = __("Date Archive", "theme");
      } else if (is_category() || taxonomy_exists('portfolio-category')) {
        $args['description'] = __("Category Archive", "theme");
      } else {
        $args['description'] = __("Archive", "theme");
      }
    } else if (is_search()) {
      $found = $GLOBALS['wp_query']->found_posts;
      $args['title'] = get_search_query();
      if ($found == 1) {
        $args['description'] = __("Search results. 1 post found.", "theme");
      } else if ($found == 0) {
        $args['description'] = __("Search results. No posts found.", "theme");
      } else {
        $args['description'] = sprintf(__("Search results. %d posts found.", "theme"), $found);
      }
    } else if (is_404()) {
      $args['title'] = __("Page Not Found", "theme");
      $args['description'] = esc_html(__("The page you're looking for can't be found.", "theme"));
    } else {
      $args['title'] = get_the_title();
      
      if ($args['display_as_desc'] == 'description') {
        $description = $der_framework->postmeta('description');
        $args['description'] = ($description) ? sprintf('<small class="meta">%s</small>', esc_html($description)) : null;
      } else {
        $args['description'] = theme_metadata_html($args['display_options']);
      }
      
    }
    
    if (!is_singular()) {
      $args['description'] = sprintf('<ul class="meteor-info clearfix"><li class="meta-description">%s</li></ul>', $args['description']);
    }
    
    if (is_home()) $args['show_breadcrumb'] = false;
    
    return $der_framework->render_template('meteor-section-title.mustache', $args);
    
  }

?>