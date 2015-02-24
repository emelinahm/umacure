<?php

if (!defined('ABSPATH')) die();

/* Core Metabox Functions */

  global $der_framework;

  // Assign metabox object to $der_framework
  $metabox = $der_framework->metabox = new Metabox();

  // Metabox queue
  $metabox_queue = array();

  // Metabox callbacks
  $metabox_callbacks = array();

  // Process $metabox_queue on 'admin_init'
  add_action('admin_init', 'theme_metabox_init');

  // Save post meta callback
  add_action( 'save_post', 'theme_metabox_save' );

  /* Automates the creation of post metaboxes */

  function theme_metabox($args) { global $metabox_queue;
  
    $defaults = array(
      'id' => null,
      'title' => null,
      'callback' => null,
      'post_type' => null, 
      'context' => 'normal',
      'priority' => 'high',
      'callback_args' => null
    );

    $args = wp_parse_args($args, $defaults);

    $metabox_queue[] = $args;

  }
  
  /* Handles the layout meta rendering */
  
  function layout_metabox_callback() { global $metabox;
  
    $metabox->layout(array(
      'key' => 'layout',
      'title' => "",
      'desc' => sprintf('Layout to use for this %s.<br/>
<p style="margin-top: 0.8em;"><small><a target="_blank" class="notd" href="?page=layout-editor">&mdash; Manage Layouts &mdash;</a></small></p>', get_post_type())
    ));
    
  }
  

  /* Handles initialization of the theme metaboxes */

  function theme_metabox_init() { global $metabox_queue, $metabox_callbacks;
    
    foreach ($metabox_queue as $metabox) {
      extract($metabox);
      $metabox_callbacks[$post_type] = $callback;
      add_meta_box($id, $title, $callback, $post_type, $context, $priority, $callback_args);
    }
    
    foreach (array('post', 'page', 'portfolio') as $post_type) {
      add_meta_box('layout-metabox', 'Layout', 'layout_metabox_callback', $post_type, 'side');
    }
    
  }

  /* Handles the post metadata saving */

  function theme_metabox_save() { global $der_framework, $metabox, $metabox_callbacks;
  
    // Do nothing if auto saving post
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Make sure the metabox nonce is set
    if ( ! $der_framework->verify_nonce() ) return;

    // Make sure the user can edit the post
    if(! @current_user_can('edit_post') ) return;
  
    // Tell the framework that we're in options-lookup mode
    if (!defined('METABOX_LOOKUP_MODE')) define('METABOX_LOOKUP_MODE', true);
    
    // Get Post ID/Type
    $post_id = $_POST['post_ID'];
    $post_type = $_POST['post_type'];
  
    // Get metabox callback using post type
    $metabox_cb = $metabox_callbacks[$post_type];
  
    // If there's no metabox callback registered, it means there isn't
    // a metabox registered for the post type. Nothing to be done.
    if ( empty($metabox_cb) ) return;
  
    // Run metabox callback to obtain the list of meta keys
    $metabox_cb();
  
    // Get meta keys registered in metabox instance by lookup mode
    $meta_keys = $metabox->keys;
    
    // Add layout meta key
    $meta_keys[] = $der_framework->key('layout');
    
    do_action('framework_save_meta', $meta_keys);
    
    // Iterate over POST values
    foreach ($meta_keys as $key) {

      // Get value from POST
      $val = stripslashes(isset($_POST[$key]) ? $_POST[$key] : '');
    
      // Retrieve current meta value from cache
      $current = get_post_meta($post_id, $key, true);
    
      // Empty values from POST are deleted
      if ( empty($val) ) {
      
        delete_post_meta($post_id, $key);
    
      // If there's no current value, set a new one
      } else if ( empty($current) ) {
      
        add_post_meta($post_id, $key, $val);
      
      // Otherwise, update an existing value
      } else {

        update_post_meta($post_id, $key, $val);

      }

    }

  }

?>