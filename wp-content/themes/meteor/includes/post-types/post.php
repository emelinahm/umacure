<?php

  add_action('manage_posts_custom_column', 'default_post_columns');
  add_filter('manage_edit-post_columns', 'default_post_columns');

  function default_post_columns($context, $id=null) { global $der_framework;

    $id = get_the_ID();
    $post_type = get_post_type();
    
    // Add new columns
    if (is_array($context)) {

      $cols = array();
      
      foreach ($context as $col => $name) {
        if ($col == 'author') {
          $cols['image'] = "Featured Image";
          $cols['description'] = "Description";
          $cols['layout'] = "Layout";
        }
        $cols[$col] = $name;
      }
      
      return $cols;
    
    // Manage Columns
    } else {
    
      $post_format = get_post_format();
      if ($post_format === false) $post_format = 'standard';
    
      switch ($context) { 
        case 'image':
          if ($post_format == 'standard' || $post_format == 'image' || $post_format == 'gallery') {
            $img = $der_framework->post_image();
            if ($img) {
               $size = ($post_type == 'icon-post') ? 50 : 100;
               $thumb = $der_framework->thumb_src($img, $size);
               printf('<a rel="lightbox[gallery]" title="%s" href="%s"><img width="' . $size . '" src="%s" /></a>', get_the_title(), $img, $thumb);
            }
          }
          break;
        case 'description':
          switch ($post_format) {
            case 'image':
              echo $der_framework->postmeta('image_description_str');
              break;
            case 'gallery':
              echo $der_framework->postmeta('gallery_description_str');
              break;
            default:
              echo $der_framework->postmeta($context);
              break;
          }
          break;
        case 'url':
        case 'order':
          echo $der_framework->postmeta($context);
          break;
        case 'layout':
          $layout = $der_framework->postmeta($context);
          
          if (empty($layout) || !$der_framework->layout_exists($layout)) {
            switch (get_post_type()) {
              case 'post':
              case 'portfolio':
                $layout = $der_framework->option('single_layout');
                break;
            }
          }
          
          if ($layout && $der_framework->layout_exists($layout)) {
            printf('<a href="%s#%s">%s</a>', admin_url('admin.php?page=layout-editor'), $layout, $layout);
          }
          break;
      }
    
    }
  
  }

?>