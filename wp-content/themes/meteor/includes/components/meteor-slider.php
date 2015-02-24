<?php

  function meteor_slider($atts, $content='', $code='') { global $der_framework;
    
    $fullwidth = ($code == 'meteor_slider_fullwidth' || $code == 'meteor_vslider_fullwidth');
    $vslider = ($code == 'meteor_vslider' || $code == 'meteor_vslider_fullwidth');
    
    $defaults = array(
      'gallery' => null,
      'image_limit' => 10,
      'fullwidth' => $fullwidth,
      'visibility' => null
    );
    
    if ($vslider) $defaults['align'] = 'right';
    
    $args = wp_parse_args($atts, $defaults);
    
    if ($fullwidth) {
      $args['width'] = (int) $der_framework->option('max_image_width');
    } else {
      $args['width'] = $der_framework->layout->get_column_width($args['slots']);
    }
    
    if (empty($args['height'])) {
      $args['height'] = floor($args['width']*GOLDEN_RATIO_FACTOR);
    }
    
    $gallery_images = $args['gallery_images'] = theme_get_gallery_images($args);
    
    if (empty($gallery_images)) return;
    
    if (isset($args['randomize']) && $args['randomize']) {
      shuffle($args['gallery_images']);
    }

    $slider_options = array_flip(array(
      'effect',
      'speed',
      'timeout',
      'easing',
      'direction',
      'reverse',
      'autoplay',
      'pauseOnHover'
    ));
    
    $args['options'] = theme_options_attr('data-options', array_intersect_key($args, $slider_options));
    
    if ($vslider) $args['first_item'] = $args['gallery_images'][0];

    switch ($code) {
      case 'meteor_slider':
      case 'meteor_slider_fullwidth':
        $template = 'meteor-slider.mustache';
        break;
      case 'meteor_vslider':
      case 'meteor_vslider_fullwidth':
        $template = 'meteor-vslider.mustache';
        break;
      default:
        return null;
        break;
    }

    return $der_framework->render_template($template, $args);
      
  }

?>