<?php

  function meteor_posts_scroller($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'easing' => 'easeInOutQuad',
      'wrap' => 'circular',
      'duration' => 400,
      'autoscroll' => false,
      'autoscroll_interval' => 3000,
      'autoscroll_pause_on_hover' => true
    );

    $args = wp_parse_args($atts, $defaults);
    
    $args['__return'] = true;
    $args['__chunks'] = false;

    $args = array_merge($args, meteor_posts($args, "", $code));
    
    // pre($args);
    
    return $der_framework->render_template('meteor-posts-scroller.mustache', $args);
    
  }

  function meteor_posts($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'category' => null,
      'custom_query' => null,
      'columns' => 4,
      'showposts' => 12,
      'pagination' => false,
      'show_thumb' => true,
      'thumb_height' => null,
      'thumb_options' => array(),
      'click_behavior' => null,
      'title_icon' => null,
      'title_align' => null,
      'show_description' => null,
      'metadata_display' => 'description',
      'show_excerpt' => null,
      'excerpt_align' => null,
      'show_link' => true,
      'title_link' => null,
      'link_align' => null,
      'link_class' => 'read-more',
      'more_text' => __("Read More", "theme")
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    // Set thumbnail dimensions
    if ($args['show_thumb']) {
      
      $args['width'] = $der_framework->layout->get_column_width($args['columns']);
      $args['height'] = ($args['thumb_height']) ? $args['thumb_height'] : floor($args['width']*GOLDEN_RATIO_FACTOR);
      
      // Thumb options
      $thumb_options = $args['thumb_options'];
      $args['opt_permalink'] = in_array('permalink', $thumb_options);
      $args['opt_lightbox'] = in_array('lightbox', $thumb_options);
      $args['opt_gallery'] = in_array('gallery', $thumb_options);
      
    }

    // Left align is the default
    if ($args['title_align'] == 'left') unset($args['title_align']);

    // Set auto alignment
    if (empty($args['excerpt_align']) && isset($args['title_align']) && $args['title_align']) $args['excerpt_align'] = $args['title_align'];
    if (empty($args['link_align']) && isset($args['title_align']) && $args['title_align']) $args['link_align'] = $args['title_align'];

    // Set query conditions
    if (preg_match('/^meteor_portfolio_posts/', $code)) { // Applies for posts & portfolio posts scroller
      $post_type = 'portfolio';
      $taxonomy = 'portfolio-category';
    } else {
      $post_type = 'post';
      $taxonomy = 'category';
    }

    $chunks = true;
    
    if (isset($args['__chunks'])) $chunks = $args['__chunks'];
    
    if ($der_framework->query_post_formats) {
      $der_framework->exclude_post_formats = array(
        'post-format-aside', 
        // 'post-format-gallery',
        'post-format-link', 
        // 'post-format-image',
        'post-format-quote',
        'post-format-status', 
        // 'post-format-video',
        'post-format-audio', 
        'post-format-chat'
      );
    }
    
    $args = component_get_posts($args, array(
      'post_type' => $post_type,
      'taxonomy' => $taxonomy,
      'permalink' => true,
      'post_class' => true,
      'chunks' => $chunks,
      'featured_image' => $args['show_thumb'],
      'pagination' => $args['pagination'],
      'showposts' => $args['showposts'],
      'excerpt' => $args['show_excerpt'],
      'meta_keys' => array('description'),
      'show_description' => $args['show_description'],
      'metadata_display' => $args['metadata_display'],
      'custom_query' => $args['custom_query']
    ));
      
    if (empty($args['title_icon']) && isset($args['rows'])) {
      foreach ($args['rows'] as $i => $row) {
        foreach ($row['posts'] as $j => $post) {
          if (empty($post['image_src'])) {
            $args['rows'][$i]['posts'][$j]['title_icon'] = 'icon-file-text-alt';
          }
        }
      }
    }

    if (isset($args['__return']) && $args['__return']) {
      
      return $args;
      
    } else {
      
      return $der_framework->render_template('meteor-posts.mustache', $args);

    }

  }

?>