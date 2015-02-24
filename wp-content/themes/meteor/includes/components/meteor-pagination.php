<?php

  function meteor_pagination($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array();
    
    $args = wp_parse_args($atts, $defaults);
    
    $args['pagination'] = $der_framework->paginate($der_framework->last_query);
    
    return $der_framework->render_template('meteor-pagination.mustache', $args);
    
  }

?>
