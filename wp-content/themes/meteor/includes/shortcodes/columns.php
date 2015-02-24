<?php

  for ($i=1; $i <= 12; $i++) {
    add_shortcode(sprintf("c%d", $i), 'meteor_columns_shortcode');
  }

  function meteor_columns_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $args = array(
      'content' => remove_empty_paragraphs($der_framework->shortcode(remove_br($content))),
      'columns' => (int) preg_replace('/[^0-9]+/', '', $code),
      'first' => in_array('first', $atts),
      'last' => in_array('last', $atts)
    );
    
    if ($code === 'c12') {
      $args['first'] = $args['last'] = true;
    }

    return $der_framework->render_template('meteor-columns.mustache', $args);
    
  }
  
?>