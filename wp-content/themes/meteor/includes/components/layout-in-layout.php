<?php

  function layout_in_layout_component($atts, $content='', $code='') { global $der_framework;
    
    $fullwidth = ($code == 'layout_in_layout_fullwidth');
    
    $defaults = array(
      'layout' => null,
      'padding_left' => 0,
      'padding_right' => 0
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $layout = $args['layout'];
    
    if (empty($layout)) return null;

    $layout = base64_decode($layout);
    
    if ($der_framework->layout_exists($layout)) {
      
      if ($fullwidth) {
        
        return $der_framework->layout($layout);
        
      } else {
        
        return $der_framework->render_nested_layout($layout, $args);
        
      }
      
    } else {
      
      return null;
      
    }
    
  }
  

?>