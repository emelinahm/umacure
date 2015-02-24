<?php

  if (!defined('ABSPATH')) die();

  global $der_framework;

  require('social-icons-common.php');

  // Verify nonce
  if ( ! $der_framework->verify_nonce() ) die('-1:1');
  
  // Load available social icons
  $icons = get_theme_social_icons();
  
  $social = $_POST['social'];
  
  if (is_array($social)) {
    
    $filtered = array();
    
    foreach ($social as $key => $arr) {
      if (array_key_exists($key, $icons) && !in_array($key, $filtered)) {
        $filtered[$key] = $arr;
      }
    }
    
    // $der_framework->delete_option('social_data');
    $der_framework->update_option('social_data', $filtered);
    
  } else {
    
    $der_framework->delete_option('social_data');
    
  }
    
  header('Location: ' . admin_url('admin.php?page=social-icons'));
    
?>