<?php

  function meteor_blog_posts($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'category' => null,
      'custom_query' => null,
      'showposts' => 12,
      'pagination' => true,
      'show_thumb' => true,
      'thumb_width' => null,
      'thumb_height' => null,
      'thumb_aspect_ratio' => 'thumb_height',
      'post_style' => 'normal',
      'thumb_options' => array(),
      'click_behavior' => null,
      'display_options' => array(),
      'show_link' => true,
      'link_class' => 'read-more',
      'more_text' => __("Read More", "theme")
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $post_style = $args['post_style'];
    $container_width = $der_framework->layout->get_column_width($args['slots']);
    $show_thumb = $args['show_thumb'];

    switch ($post_style) {
      case 'normal':
        $args['post_style_normal'] = true;
        if ($show_thumb) $args['width'] = $container_width;
        break;
      case 'thumb_aside':
        $args['post_style_aside'] = true;
        if ($show_thumb) {
          if ($args['thumb_width']) {
            $args['width'] = (int) $args['thumb_width'];
            if ($args['width'] > $container_width) $args['width'] = $container_width;
          } else {
            $args['width'] = floor($container_width/4);
          }
        }
        break;
    }
    
    // Set thumb height based on aspect ratio
    if ($show_thumb) theme_set_thumb_aspect_ratio($args);
    
    // Set thumb options
    theme_set_thumb_options($args);
    
    // Set query options
    if ($code == 'blog_portfolio_posts') {
      $post_type = 'portfolio';
      $taxonomy = 'portfolio-category';
    } else {
      $post_type = 'post';
      $taxonomy = 'category';
    }

    $query_posts = ($code == 'query_posts');

    $args = component_get_posts($args, array(
      'default_query' => $query_posts,
      'post_type' => $post_type,
      'taxonomy' => $taxonomy,
      'custom_query' => $args['custom_query'],
      'featured_image' => $args['show_thumb'],
      'chunks' => false,
      'excerpt' => true,
      'show_description' => false,
      'permalink' => true,
      'edit_post_link' => true,
      'post_class' => true,
      'post_formats' => true,
      'pagination' => $args['pagination']
    ));
      
    if (empty($args['posts'])) {
      $args['no_posts_content'] = meteor_404(array(
        'icon' => "icon-info-sign",
        'error_label' => __("There were no results for your query", "theme"),
        'error_description' => __("You may need to adjust your search criteria to increase your chances", "theme") . '. ' . __("Try searching again", "theme") . ':', 
      ));
    } else {
      $args['show_pagination'] = true;
    }
      
    // Pagination
    if ($args['pagination']) {
      $args['pagination'] = $der_framework->paginate($der_framework->last_query);
    }
      
    // pre($args);
      
    return $der_framework->render_template('meteor-blog-posts.mustache', $args);
    
  }

?>