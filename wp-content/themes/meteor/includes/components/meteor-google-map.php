<?php

  add_shortcode('google_map', 'meteor_google_map');

  function meteor_google_map($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'latlong' => null,
      'type' => 'ROADMAP',
      'zoom' => 16,
      'height' => 400,
      'title' => '',
      'link' => '',
      'content' => '',
      'tooltip' => '',
      'fullscreen' => false
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $der_framework->load_maps_api = true; // Make sure the google maps api script is loaded
    
    if (empty($args['content']) && $content) $args['content'] = $content;
    
    if ($code == 'google_map_fullwidth') $args['fullwidth'] = true;
    
    $args['title'] = str_replace('"', '\\"', $args['title']);
    $args['content'] = $der_framework->content(str_replace('"', '\\"', $args['content']), false); // No shortcodes
    $args['tooltip'] = str_replace('"', '\\"', $args['tooltip']);

    $args['border_color'] = 'transparent';
    
    $data = array(
      'latlong' => (string) $args['latlong'],
      'type' => $args['type'],
      'zoom' => (int) $args['zoom'],
      'title' => $args['title'],
      'link' => $args['link'],
      'tooltip' => $args['tooltip'],
      'fullscreen' => $args['fullscreen']
    );

    $json = json_encode($data);
  
    $args['json'] = $json;
  
    return $der_framework->render_template('meteor-google-map.mustache', $args);
  
  }

?>