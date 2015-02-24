<?php

  function meteor_logo_scroller($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'columns' => 3,
      'easing' => 'easeInOutQuad',
      'wrap' => 'both',
      'width_adjust' => null,
      'duration' => 400,
      'autoscroll' => false,
      'autoscroll_interval' => 3000,
      'autoscroll_pause_on_hover' => true
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $args['norender'] = true;
    $args['chunks'] = false;
    
    $args['items'] = meteor_logo_display($args, "", 'meteor_logo_scroller');
    $args['visual_columns'] = floor(LAYOUT_GRID_SIZE/$args['columns']);
    $args['lightbox'] = ($args['enable_links'] && $args['click_behavior'] == 'lightbox');
    
    return $der_framework->render_template('meteor-logo-scroller.mustache', $args);
    
  }
  
  function meteor_logo_display($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'gallery' => null,
      'columns' => 3,
      'image_limit' => 12,
      'resize_images' => true,
      'enable_tooltips' => true,
      'enable_links' => true,
      'click_behavior' => 'follow',
      'opacity_effect' => true,
      'min_opacity' => 0.60,
      'max_opacity' => 1,
      'randomize' => false
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    if ($args['resize_images']) {
      $args['width'] = $der_framework->layout->get_column_width($args['columns']);
      $args['height'] = 0;
    } else {
      $args['nocrop'] = true;
    }

    $args['chunks'] = (array_key_exists('chunks', $args)) ? $args['chunks'] : true;
    
    $args['rows'] = theme_get_gallery_images($args);
    
    if (isset($args['norender']) && $args['norender']) return $args['rows'];
    
    $args['lightbox'] = ($args['enable_links'] && $args['click_behavior'] == 'lightbox');
      
    return $der_framework->render_template('meteor-logo-display.mustache', $args);
    
  }

?>