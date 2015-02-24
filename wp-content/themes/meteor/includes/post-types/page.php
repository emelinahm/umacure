<?php

  add_action('manage_pages_custom_column', 'default_page_columns');
  add_filter('manage_edit-page_columns', 'default_page_columns');

  function default_page_columns($context, $id=null) { global $der_framework;

    $id = get_the_ID();
    $post_type = get_post_type();

    // Add new columns
    if (is_array($context)) {

      $cols = array();

      foreach ($context as $col => $name) {
        if ($col == 'author') {
          // $cols['image'] = "Featured Image";
          $cols['description'] = "Description";
          $cols['layout'] = "Layout";
        }
        $cols[$col] = $name;
      }

      return $cols;

      // Manage Columns
    } else {

      switch ($context) { 
        case 'layout':
          $layout = $der_framework->postmeta($context);
          if (empty($layout) || !$der_framework->layout_exists($layout)) $layout = $der_framework->option('page_layout');
          if ($layout && $der_framework->layout_exists($layout)) {
            printf('<a href="%s#%s">%s</a>', admin_url('admin.php?page=layout-editor'), $layout, $layout);
          }
          break;
        case 'description':
          echo $der_framework->postmeta($context);
          break;
      }

    }

  }
  
?>