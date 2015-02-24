<?php

  if (!defined('ABSPATH')) die();

  global $der_framework;
  
  switch ($_POST['context']) {
    
    case 'add-color-theme':
      extract(shortcode_atts(array('name' => null), $_POST));
      if ($name) die(json_encode($der_framework->add_color_theme($name)));
      break;

    case 'rename-color-theme':
      extract(shortcode_atts(array('id'=>null, 'name' => null), $_POST));
      if ($id && $name) die(json_encode($der_framework->rename_color_theme($id, $name)));
      break;
      
    case 'delete-color-theme':
      extract(shortcode_atts(array('id' => null), $_POST));
      if ($id) die(json_encode($der_framework->delete_color_theme($id)));
      break;
      
    case 'update-color-theme-css':
      extract(shortcode_atts(array('css' => null), $_POST));
      if ($css) {
        $der_framework->update_color_theme_css($css);
        die(json_encode(array('success' => true)));
      }
      break;
      
    case 'get-color-theme-data':
      die(json_encode($der_framework->get_color_theme_data()));
      break;
      
  }
  
  die('{}');
  
?>