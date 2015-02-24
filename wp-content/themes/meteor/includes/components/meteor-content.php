<?php

  function meteor_content($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'content' => null
    );

    $args = wp_parse_args($atts, $defaults);
    
    if ($code === 'content_fullwidth') $args['fullwidth'] = true;
    
    $args['content'] = $der_framework->content($args['content']);

    return $der_framework->render_template('meteor-content.mustache', $args);

  }

?>