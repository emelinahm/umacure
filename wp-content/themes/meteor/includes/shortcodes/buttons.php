<?php

  add_shortcode('button', 'meteor_button');
  add_shortcode('capsule', 'meteor_button');

  function meteor_button($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'icon' => null,
      'url' => null,
      'id' => null,
      'class' => null,
      'style' => '',
      'text' => $content
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    if ($code == 'button') {
      if (in_array('large', $atts)) {
        $args['size'] = 'large';
      } else if (in_array('small', $atts)) {
        $args['size'] = 'small';
      } else {
        $args['size'] = 'medium';
      }
    }
    
    if (isset($args['icon'])) $args['icon'] = preg_replace('/^icon-/', '', $args['icon']);

    if (in_array('fullwidth', $atts) || in_array('fw', $atts)) $args['fullwidth'] = true;

    if (in_array('icon-right', $atts)) $args['icon_right'] = true;
    
    if (in_array('external', $atts)) $args['external'] = true;
    
    switch ($code) {
      case 'button': $args['button_class'] = sprintf("meteor-button %s", $args['size']); break;
      case 'capsule': $args['button_class'] = "meteor-capsule"; break;
    }
    
    return $der_framework->render_template('meteor-button.mustache', $args);

  }

?>