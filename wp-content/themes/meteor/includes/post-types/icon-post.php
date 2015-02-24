<?php

  /* Icon Posts */
  
  global $der_framework;
  
  $der_framework->add_post_type(array(
    'slug' => 'icon-post',
    'singular_name' => 'Icon Post',
    'plural_name' => 'Icon Posts',
    'description' => 'Posts with associated icons',
    'columns_callback' => 'post_icon_columns',
    'args' => array(
      'public' => false,
      'show_ui' => true,
      'menu_icon' => $der_framework->uri('core/images/admin/icon-post-icon.png', THEME_VERSION),
      'supports' => 'title, editor, thumbnail'
    )
  ));
  
  $der_framework->add_taxonomy(array(
    'slug' => 'icon-post-category',
    'post_type' => 'icon-post',
    'singular_name' => "Icon Post Category",
    'plural_name' => "Icon Post Categories",
    'args' => array('hierarchical' => true)
  ));
  
  function post_icon_columns($context, $id=null) { global $der_framework;

    $id = get_the_ID();
    
    // Add new columns
    if (is_array($context)) {

      $cols = array();
      
      foreach ($context as $col => $name) {
        if ($col == 'date') {
          $cols['icon'] = "Icon";
          $cols['image'] = "Featured Image";
          $cols['description'] = "Description";
          $cols['category'] = "Category";
          $cols['url'] = "URL";
          $cols['order'] = "Order";
        }
        $cols[$col] = $name;
      }
      
      return $cols;
    
    // Manage Columns
    } else {
    
      switch ($context) { 
        case 'category':
          theme_list_taxonomies_column($id, 'icon-post-category');
          break;
        case 'icon':
          $icon = $der_framework->postmeta('icon');
          if ($icon) printf('<i class="%s list-table-icon"></i>', $icon);
          break;
      }
    
    }
  
  }
  
?>