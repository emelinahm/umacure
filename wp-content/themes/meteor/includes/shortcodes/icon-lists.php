<?php

  add_shortcode('icon_list', 'meteor_icon_list');

  function meteor_icon_list($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;

    $defaults = array(
      'icon' => 'ok',
      'color' => '@accent',
      'size' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    
    $content = remove_br($content, true);
    $content = preg_split('/^[ ]*\*/m', $content);
    $content = array_filter($content);
    
    $items = array();
    
    foreach ($content as $item) {
      $items[] = array('item' => trim($item));
    }
    
    $args['items'] = $items;
    
    if (isset($args['size']) && preg_match('/^\d+$/', $args['size'])) $args['size'] .= 'px';
    
    if (isset($args['icon'])) $args['icon'] = preg_replace('/^icon-/', '', $args['icon']);
    
    if (isset($args['color'])) {
      $color = trim($args['color']);
      if (preg_match('/^@/', $color)) {
        unset($args['color']);
        $args['color_class'] = preg_replace('/^@/', '', $color);
      }
    }
    
    return $der_framework->render_template('meteor-icon-list.mustache', $args);
    
  }
  
?>