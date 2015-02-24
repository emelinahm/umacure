<?php

  global $meteor_icon_big_style;

  add_shortcode('icon', 'meteor_icon_shortcode');
  add_shortcode('icon_big', 'meteor_big_icon_shortcode');
  add_shortcode('icon_big_style', 'meteor_big_icon_shortcode');

  $meteor_icon_big_style = null;

  function meteor_big_icon_shortcode($atts, $content='', $code='') { global $der_framework, $meteor_icon_big_style;

    $atts = (array) $atts;

    $defaults = isset($meteor_icon_big_style) ? $meteor_icon_big_style : array(
      'icon' => 'plus',
      'url' => null,
      'size' => null,
      'title' => null,
      'title_size' => null,
      'padding' => 30,
      'tooltip' => false,
      'color' => '@bg',
      'hover_color' => null,
      'background' => null,
      'hover_background' => null,
      'border_color' => null,
      'border_hover_color' => null,
      'border_width' => 6,
      'border_hover_width' => 12,
      'border_style' => 'solid'
    );

    $args = shortcode_atts($defaults, $atts);
    
    if ($code === 'icon_big_style') {
      
      $meteor_icon_big_style = $args;
      
      $out = $der_framework->shortcode(remove_br($content, true));
      
      $meteor_icon_big_style = null;
      
      return $out;
      
    } else {
      
      $args['has_border'] = (isset($args['border_width']) && isset($args['border_color']));
      
      if ($args['has_border']) {
        $args['padding'] += $args['border_width'];
      }
      
      foreach (array('size', 'title_size', 'padding', 'border_width', 'border_hover_width') as $opt) {
        if (isset($args[$opt]) && preg_match('/^\d+$/', $args[$opt])) $args[$opt] .= 'px';
      }
      
      if (isset($args['color'])) {
        $color = trim($args['color']);
        if (preg_match('/^@/', $color)) {
          unset($args['color']);
          $args['color_class'] = preg_replace('/^@/', '', $color);
        }
      }
      
      return $der_framework->render_template('meteor-icon-big.mustache', $args);
      
    }

  }

  function meteor_icon_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'size' => null,
      'shape' => null,
      'color' => null,
      'background' => null,
      'margin' => null,
      'padding' => 10
    );

    $args = shortcode_atts($defaults, $atts);
    
    foreach ($atts as $key => $val) {
      if (is_integer($key)) $args['icon'] = $val;
    }
    
    if (isset($args['icon'])) {
      
      $args['padding'] = (int) $args['padding'];
      
      $args['icon'] = preg_replace('/^icon-/', '', $args['icon']);
      
      if (empty($args['size']) && $args['shape'] === 'circle') {
        $args['size'] = 20;
        $args['padding'] = 8;
      }
      
      if (isset($args['color'])) {
        $color = trim($args['color']);
        if (preg_match('/^@/', $color)) {
          unset($args['color']);
          $args['color_class'] = preg_replace('/^@/', '', $color);
        }
      }
      
      if (isset($args['background'])) {
        $bg = trim($args['background']);
        if (preg_match('/^@/', $bg)) {
          unset($args['background']);
          $args['bg_class'] = preg_replace('/^@/', '', $bg) . '-bg';
        }
      }
      
      if (isset($args['margin'])) $args['margin'] = preg_replace('/;[ ]*$/', '', $args['margin']);

      if (isset($args['size']) && preg_match('/^\d+$/', $args['size'])) $args['size'] .= 'px';
    
      switch ($args['shape']) {
        case 'circle': $args['is_circle'] = true; break;
        case 'hex': $args['is_hex'] = true; break;
        default: $args['is_default'] = true; break;
      }
      
      return $der_framework->render_template('meteor-icon.mustache', $args);
      
    } else {
      
      return null;
      
    }
    
  }
  
?>