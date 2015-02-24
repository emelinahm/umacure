<?php

  /* Gallery Post Type */
  
  global $der_framework;
  
  $der_framework->add_post_type(array(
    'slug' => 'gallery',
    'singular_name' => 'Gallery',
    'plural_name' => 'Galleries',
    'description' => 'Gallery post type',
    'columns_callback' => 'gallery_columns',
    'args' => array(
      'public' => false,
      'show_ui' => true,
      'menu_icon' => $der_framework->uri('core/images/admin/gallery-post-icon.png', THEME_VERSION),
      'supports' => 'title'
    )
  ));
  
  function gallery_columns($context, $id=null) { global $der_framework;

    $id = get_the_ID();
    
    // Add new columns
    if (is_array($context)) {

      $cols = array();
      
      foreach ($context as $col => $name) {
        if ($col == 'date') {
          $cols['shortcode'] = "Shortcode";
          $cols['gallery_images'] = "Gallery Images";
        }
        $cols[$col] = $name;
      }
      
      return $cols;
    
    // Manage Columns
    } else {
    
      switch ($context) {
        case 'shortcode':
          printf('<span style="font-family: monospace;">[meteor_gallery id="%d"]<span>', get_the_ID());
          break;
        case 'gallery_images':
          $gallery = json_decode($der_framework->postmeta('gallery_images'), true);
          if ($gallery) {
            $count = count($gallery);
            if ($count === 1) printf('<a rel="lightbox" href="%s">1 Image', $gallery[0]['image']);
            else {
              $id = get_the_ID();
              printf('<a rel="lightbox[post-%d]" href="%s">%d Images</a>', $id, $gallery[0]['image'], $count);
              for ($i=1; $i < $count; $i++) {
                printf('<a style="display: none;" rel="lightbox[post-%d]" href="%s">%d Images</a>', $id, $gallery[$i]['image'], $count);
              }
            }
          }
          break;
      }
    
    }
  
  }
  
?>