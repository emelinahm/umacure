<?php

  function meteor_cta($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'title' => null,
      'description' => null,
      'icon' => null,
      'link_text' => __("Read More", "theme"),
      'url' => null,
      'text_mode' => 'plain',
      'link_class' => 'meteor-button medium'
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    if ($args['text_mode'] == 'plain') {
      $args['title'] = esc_html($args['title']);
      $args['description'] = esc_html($args['description']);
    }
    
    // Parse icons
    foreach(array('title', 'description', 'link_text') as $context) {
      $args[$context] = theme_mini_shortcode($args[$context]);
    }
    
    // Add Icon
    if ($args['icon']) $args['link_text'] = sprintf('<i class="%s"></i> &nbsp; %s', $args['icon'], $args['link_text']);
    
    switch ($code) {
      case 'meteor_cta': $template = 'meteor-cta.mustache'; break;
      case 'meteor_cta_fullwidth': $template = 'meteor-cta-fullwidth.mustache'; break;
    }
    
    return $der_framework->render_template($template, $args);
    
  }

?>