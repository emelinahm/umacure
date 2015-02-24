<?php

  add_shortcode('testimonial', 'meteor_testimonial');
  add_shortcode('testimonials', 'meteor_testimonial_container');
  
  function meteor_testimonial_container($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'timeout' => 4000
    );
    
    $args = wp_parse_args($atts, $defaults);

    $options = array('touch' => true);

    if (in_array('autoplay', $atts)) {
      $options['autoplay'] = true;
      if (in_array('pause_on_hover', $atts)) $options['pauseOnHover'] = true;
      $options['timeout'] = $args['timeout'];
    }
    
    $der_framework->runtime->testimonial_inside = true;
    
    $args = array(
      'single' => true,
      'container' => true,
      'options' => theme_options_attr("data-options", $options),
      'content' => $der_framework->content(remove_br($content))
    );
    
    $der_framework->runtime->testimonial_inside = false;

    return $der_framework->render_template('meteor-testimonials.mustache', $args);
    
  }

  function meteor_testimonial($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'from' => null,
      'description' => null,
      'url' => null
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    if (isset($der_framework->runtime->testimonial_inside) && $der_framework->runtime->testimonial_inside) {
      $args['single'] = false;
    } else if (in_array('single', $atts)) {
      $args['single'] = true;
    }
    
    
    $args['content'] = $der_framework->content(remove_br($content));

    return $der_framework->render_template('meteor-testimonials.mustache', $args);
    
  }

?>