<?php

  function meteor_sidebar($atts, $content='') { global $der_framework;
    
    $defaults = array(
      'sidebars' => '',
      'sidebars_override' => '',
      'padding_left' => 0,
      'padding_right' => 0,
      'hide_on_mobile' => false
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $sidebars = $args['sidebars'];
    
    if (empty($sidebars)) $sidebars = $args['sidebars_override'];
    
    $sidebars = csv2array(str_replace('___', ' ', $sidebars)); // de-sanitize
    
    // Using a cached template to improve performance. 
    // This is a rather simple template, but every bit of 
    // performance saved counts.
    
    $template = 'meteor-sidebar.mustache';

    echo $der_framework->render_template($template, array(
      'open' => true,
      'visibility' => (isset($args['visibility'])) ? $args['visibility'] : null,
      'inline_css' => (isset($args['inline_css'])) ? $args['inline_css'] : null,
      'container_class' => $args['container_class']
    ));
    
    foreach ($sidebars as $sidebar) {
      dynamic_sidebar($sidebar);
    }
    
    echo $der_framework->render_template($template, array(
      'close' => true
    ));

  }

?>