<?php

  function meteor_aside_posts($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'category' => null,
      'custom_query' => null,
      'showposts' => 12,
      'pagination' => false,
      'show_thumb' => true,
      'thumb_width' => 90,
      'thumb_height' => 74,
      'thumb_options' => array(),
      'click_behavior' => null,
      'show_description' => null,
      'metadata_display' => 'description',
      'show_excerpt' => true,
      'show_link' => true,
      'title_link' => true,
      'link_class' => 'read-more',
      'more_text' => __("Read More", "theme")
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    // Set thumbnail dimensions
    if ($args['show_thumb']) {
      $margin = 12;
      $args['width'] = $args['thumb_width'];
      $args['height'] = ($args['thumb_height']) ? $args['thumb_height'] : floor($args['thumb_width']*GOLDEN_RATIO_FACTOR);
      $args['padding'] = $args['width'] + 15;
    }
    
    // Set small read more
    if ($args['link_class'] == 'read-more' && !$args['show_excerpt']) {
      $args['link_class'] .= ' black small';
    }
    
    // Thumb options
    $thumb_options = $args['thumb_options'];
    $args['opt_permalink'] = in_array('permalink', $thumb_options);
    $args['opt_lightbox'] = in_array('lightbox', $thumb_options);
    $args['opt_gallery'] = in_array('gallery', $thumb_options);
    
    // Query options
    if ($code === 'meteor_aside_portfolio_posts') {
      $post_type = 'portfolio';
      $taxonomy = 'portfolio-category';
    } else {
      $post_type = 'post';
      $taxonomy = 'category';
    }
    
    $args = component_get_posts($args, array(
      'post_type' => $post_type,
      'taxonomy' => $taxonomy,
      'chunks' => false,
      'post_class' => true,
      'custom_query' => $args['custom_query'],
      'pagination' => $args['pagination'],
      'featured_image' => $args['show_thumb'],
      'excerpt' => $args['show_excerpt'],
      'show_description' => $args['show_description'],
      'permalink' => ($args['show_link'] || $args['title_link']),
      'metadata_display' => $args['metadata_display']
    ));
      
    if (empty($args['title_icon']) && isset($args['posts'])) {
      foreach ($args['posts'] as $i => $post) {
        if (empty($post['image_src'])) $args['posts'][$i]['title_icon'] = 'icon-file-text-alt';
      }
    }
      
    return $der_framework->render_template('meteor-aside-posts.mustache', $args);
    
  }

?>