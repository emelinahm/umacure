<?php

  if (!defined('ABSPATH')) die();
  
  global $der_framework;
  
  $source = 'http://' . THEME_UPDATE_SERVER . '/webfonts.json';

  $json = @file_get_contents($source);
  
  $data = json_decode($json, true);

  if (is_array($data) && $data['kind'] == 'webfonts#webfontList') {
    
    // Make sure we only write the file if we got valid data

    @file_put_contents($der_framework->path('framework/webfonts/webfonts.json.txt'), $json);

    header('Content-Type: application/json');
    
    echo $json;
    
  } else {
    
    die('-1');
    
  }
  
  exit();
  
?>