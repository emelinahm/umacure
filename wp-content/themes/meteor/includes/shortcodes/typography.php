<?php

  add_shortcode('center', 'meteor_center_shortcode');
  add_shortcode('heading', 'meteor_heading_shortcode');
  add_shortcode('text', 'meteor_heading_shortcode');
  add_shortcode('line', 'meteor_line_shortcode');
  add_shortcode('separator', 'meteor_separator_shortcode');

  function meteor_line_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'color' => null,
      'margin' => '1.8em 0'
    );

    $args = shortcode_atts($defaults, $atts);
    
    if (isset($args['color'])) {
      $color = trim($args['color']);
      if (preg_match('/^@/', $color)) {
        unset($args['color']);
        $args['color_class'] = preg_replace('/^@/', '', $color);
      }
    }
    
    if (isset($args['margin'])) $args['margin'] = preg_replace('/;[ ]*$/', '', $args['margin']);

    return $der_framework->render_template('meteor-line.mustache', $args);
    
  }

  function meteor_center_shortcode($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;

    $defaults = array(
      'margin' => null
    );

    $args = shortcode_atts($defaults, $atts);
    
    $style = (isset($args['margin'])) ? sprintf(' style="margin: %s !important;"', $args['margin']) : "";
    
    return "\n".'<div class="text-center"'. $style .'>'."\n" . $der_framework->shortcode(remove_br($content)) . "\n".'</div><!-- .text-center-->'."\n";
  }
  
  function meteor_separator_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'icon' => null,
      'margin' => '1.5em 0',
      'color' => null,
      'linecolor' => null,
      'linesep' => null,
      'size' => null
    );

    $args = shortcode_atts($defaults, $atts);
    
    if (isset($args['icon'])) $args['icon'] = preg_replace('/^icon-/', '', $args['icon']);
    
    if (isset($args['color'])) {
      $color = trim($args['color']);
      if (preg_match('/^@/', $color)) {
        unset($args['color']);
        $args['color_class'] = preg_replace('/^@/', '', $color);
      }
    }
    
    if (isset($args['linecolor'])) {
      $color = trim($args['linecolor']);
      if (preg_match('/^@/', $color)) {
        unset($args['linecolor']);
        $args['line_color_class'] = preg_replace('/^@/', '', $color);
      }
    }
    
    foreach (array('size', 'linesep') as $opt) {
      if (isset($args[$opt]) && preg_match('/^\d+$/', $args[$opt])) $args[$opt] .= 'px';
    }
    
    if (isset($args['margin'])) $args['margin'] = preg_replace('/;[ ]*$/', '', $args['margin']);
    
    return $der_framework->render_template('meteor-separator.mustache', $args);
    
  }
  
  
  function meteor_heading_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'color' => null,
      'size' => null,
      'align' => null,
      'weight' => 300,
      'margin' => null,
      'text' => null,
    );

    $args = shortcode_atts($defaults, $atts);
    
    if (isset($args['color'])) {
      $color = trim($args['color']);
      if (preg_match('/^@/', $color)) {
        unset($args['color']);
        $args['color_class'] = preg_replace('/^@/', '', $color);
      }
    }
    
    if (isset($args['size']) && preg_match('/^\d+$/', $args['size'])) $args['size'] .= 'px';
    
    if (isset($args['margin'])) $args['margin'] = preg_replace('/;[ ]*$/', '', $args['margin']);
    
    $content = (isset($args['text'])) ? $args['text'] : $content;
    
    switch ($code) {
      case 'heading':
        $args['class'] = 'alternate-font';
        $args['element'] = 'h2';
        $args['is_heading'] = true;
        $args['content'] = remove_br(theme_mini_shortcode($content), true);
        break;
      case 'text':
        $args['class'] = 'body-font';
        $args['element'] = 'div';
        $args['content'] = $der_framework->shortcode(remove_br($content));
        break;
    }
    
    return $der_framework->render_template('meteor-heading.mustache', $args);
    
  }
  
?>