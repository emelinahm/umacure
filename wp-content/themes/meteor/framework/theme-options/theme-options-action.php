<?php

if (!defined('ABSPATH')) die();

global $der_framework;

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET' && (empty($_GET['reset-settings']) OR empty($_GET['options-context']))) die('-1:0');
if ($method == 'POST' && empty($_POST['options-context'])) die('-1:1');

if ($method == 'POST') {
  
  ////////////////////////////////////////////
  // SAVE SETTINGS 
  ////////////////////////////////////////////
  
  // Get context from POST
  $context = $_POST['options-context'];

  // Set options context
  $der_framework->set_context($context);

  // Get context key
  $context_key = $der_framework->context_key();

  // Exit if context key not set in POST
  if (!isset($_POST[$context_key])) die('-1:4');

  if (isset($_POST['reset-settings']) AND $_POST['reset-settings'] === 'true') {

    // Reset Options

    update_option($context_key, array());
    
    do_action('theme_options_reset', $context);

    exit('success');

  } else {

    // Save Options

    $options = $_POST[$context_key];
    
    $options = apply_filters($context . '-save', $options);
    
    do_action('theme_options_before_save', $context, $options);
    
    update_option($context_key, $options);
    
    do_action('theme_options_save', $context, $options);

    if (isset($_POST['ajax']) && $_POST['ajax'] === 'true') {

      exit('success');

    } else if (isset($_POST['_wp_http_referer'])) {

      header('Location: ' . $_POST['_wp_http_referer'] . '&success=true');

    } else {

      die('1');

    }

  }

} else {
  
  ////////////////////////////////////////////
  // RESET SETTINGS 
  ////////////////////////////////////////////
  
  // Get context from GET
  $context = $_GET['options-context'];
  
  // Get context key
  $context_key = $der_framework->context_key();

  update_option($context_key, array());
  
  header('Location: ' . admin_url(sprintf('admin.php?page=%s', $der_framework->options_context)));
  
}

?>