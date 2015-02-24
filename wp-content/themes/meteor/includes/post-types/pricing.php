<?php

  /* Pricing Posts */
  
  global $der_framework;
  
  $der_framework->add_post_type(array(
    'slug' => 'pricing',
    'singular_name' => ' Pricing Package',
    'plural_name' => 'Pricing Packages',
    'description' => 'Packages to be used when displaying pricing options.',
    'columns_callback' => 'pricing_columns',
    'args' => array(
      'public' => false,
      'show_ui' => true,
      'menu_icon' => $der_framework->uri('core/images/admin/pricing-post-icon.png', THEME_VERSION),
      'supports' => 'title, editor',
      'labels' => array(
        'menu_name' => 'Pricing'
      )
    )
  ));
  
  $der_framework->add_taxonomy(array(
    'slug' => 'pricing-category',
    'post_type' => 'pricing',
    'singular_name' => "Package Category",
    'plural_name' => "Package Categories",
    'args' => array('hierarchical' => true)
  ));
  
  function pricing_columns($context, $id=null) { global $der_framework;

    $id = get_the_ID();
    
    // Add new columns
    if (is_array($context)) {

      $cols = array();
      
      foreach ($context as $col => $name) {
        if ($col == 'date') {
          $cols['price'] = "Price";
          $cols['method'] = "Billing Method";
          $cols['active'] = "Active Package";
          $cols['category'] = "Package Category";
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
          theme_list_taxonomies_column($id, 'pricing-category');
          break;
        case 'price':
        case 'method':
          echo $der_framework->postmeta($context);
          break;
        case 'active':
          $active = $der_framework->postmeta_bool($context);
          if ($active) echo '<span style="margin-left: 2.5em; font-size: 1.25em;"><i class="icon-ok"></i></span>';
          break;
      }
    
    }
  
  }
  
?>