<?php

  function meteor_image_icon_posts($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'category' => null,
      'columns' => 3,
      'showposts' => 12,
      'pagination' => false,
      'shape_class' => null,
      'shape_effect_class' => null,
      'thumb_width' => 50,
      'thumb_height' => 50,
      'align' => 'left',
      'show_description' => false,
      'show_excerpt' => true,
      'excerpt_align' => false,
      'show_link' => true,
      'title_link' => false,
      'icon_link' => false,
      'link_align' => false,
      'link_class' => 'read-more',
      'more_text' => __("Read More", "theme")
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    // Variables
    $width = $height = 40;
    $margin = 14;
    
    // Size & Dimensions
    
    $args['width'] = (int) $args['thumb_width'];
    $args['height'] = (int) $args['thumb_height'];
    
    if ($args['height'] === 0) {
      $args['height'] = floor($args['width']*GOLDEN_RATIO_FACTOR);
    }

    if ($args['align'] != 'center') {
      $args['padding'] = $args['width'] + $margin;
    }
    
    $meta_keys = array('url');
    
    if ($args['show_description']) $meta_keys[] = 'description';
    
    $args = component_get_posts($args, array(
      'post_type' => 'icon-post',
      'taxonomy' => 'icon-post-category',
      'featured_image' => true,
      'optimize_image_size' => false,
      'excerpt' => $args['show_excerpt'],
      'meta_keys' => $meta_keys,
      'pagination' => $args['pagination']
    ));
      
    // pre($args);
      
    return $der_framework->render_template('meteor-icon-posts-image.mustache', $args);
    
  }

  function meteor_vector_icon_posts($atts, $content='', $code='') { global $der_framework;
  
    $defaults = array(
      'category' => null,
      'columns' => 3,
      'showposts' => 12,
      'pagination' => false,
      'shape' => 'hex',
      'size_factor' => 1,
      'align' => 'left',
      'show_description' => false,
      'show_excerpt' => true,
      'excerpt_align' => false,
      'show_link' => true,
      'title_link' => false,
      'icon_link' => false,
      'link_align' => false,
      'link_class' => 'read-more',
      'more_text' => __("Read More", "theme")
    );
  
    $args = wp_parse_args($atts, $defaults);
  
    // Shapes
    $args['hex'] = ($args['shape'] == 'hex');
    $args['circle'] = ($args['shape'] == 'circle');
    $args['circle_frame'] = ($args['shape'] == 'circle_frame');
    $args['noshape'] = ($args['shape'] == 'none');

    // Set center alignment if using Telescope shape
    if ($args['circle_frame']) $args['align'] = 'center';

    // Set color for hex shape
    if ($args['shape'] == 'hex') $args['color'] = $der_framework->color_theme_option('black', '#292929');

    // Variables
    $width = 40;
    $margin = 14;
    $font_size = ($args['noshape']) ? 30 : 14;
    $height = ($args['circle'] || $args['circle_frame']) ? 40 : 34;
    $size_factor = $args['size_factor'];
    $args['icon_glyphs'] = ($code == 'vector_icon_posts');
    
    // Increase font size for Telescope shape
    if ($args['circle_frame']) $font_size = floor($font_size*2.3);
    
    // Size & Dimensions
    $args['font_size'] = floor($size_factor*$font_size);
    $args['width'] = floor($size_factor*$width);
    $args['height'] = floor($size_factor*$height);
    
    if ($args['align'] != 'center') {
      $args['padding'] = $args['width'];
      $args['padding'] += ($args['noshape']) ? $margin : floor($margin*(1 + $size_factor*0.3));
    }
    
    $meta_keys = array('icon', 'url');
    
    if ($args['show_description']) $meta_keys[] = 'description';
    
    $args = component_get_posts($args, array(
      'post_type' => 'icon-post',
      'taxonomy' => 'icon-post-category',
      'excerpt' => $args['show_excerpt'],
      'meta_keys' => $meta_keys,
      'pagination' => $args['pagination']
    ));
      
    return $der_framework->render_template('meteor-icon-posts-vector.mustache', $args);
  
  }

?>