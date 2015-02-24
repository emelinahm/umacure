<?php

  /* Portfolio Posts */
  
  global $der_framework;
  
  $der_framework->add_post_type(array(
    'slug' => 'portfolio',
    'singular_name' => 'Portfolio Post',
    'plural_name' => 'Portfolio Posts',
    'description' => 'Portfolio posts to showcase your work',
    'columns_callback' => 'portfolio_columns',
    'args' => array(
      'public' => true,
      'show_ui' => true,
      'menu_icon' => $der_framework->uri('core/images/admin/portfolio-post-icon.png', THEME_VERSION),
      'supports' => 'title, editor, thumbnail, comments',
      'labels' => array(
        'menu_name' => 'Portfolio'
      )
    )
  ));
  
  $der_framework->add_taxonomy(array(
    'slug' => 'portfolio-category',
    'post_type' => 'portfolio',
    'singular_name' => "Portfolio Category",
    'plural_name' => "Portfolio Categories",
    'args' => array('hierarchical' => true)
  ));
  
  function portfolio_columns($context, $id=null) { global $der_framework;

    $id = get_the_ID();
    
    // Add new columns
    if (is_array($context)) {

      $cols = array();
      
      foreach ($context as $col => $name) {
        if ($col == 'comments') {
          $cols['image'] = "Featured Image";
          $cols['description'] = "Description";
          $cols['layout'] = "Layout";
          $cols['gallery'] = "Gallery";
          $cols['category'] = "Category";
        }
        $cols[$col] = $name;
      }
      
      return $cols;
    
    // Manage Columns
    } else {
    
      switch ($context) { 
        case 'category':
          theme_list_taxonomies_column($id, 'portfolio-category');
          break;
        case 'gallery':
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