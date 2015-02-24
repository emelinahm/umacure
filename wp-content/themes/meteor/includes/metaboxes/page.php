<?php

  /* Page Metabox */
  
  theme_metabox(array(
    'id' => 'page-metabox',
    'title' => 'Page Details',
    'callback' => 'page_metabox_callback',
    'post_type' => 'page',
  ));
  
  function page_metabox_callback() { global $metabox;
    
    $metabox->set_nonce();
    
    $metabox->text(array(
      'key' => 'description',
      'title' => "Description",
      'desc' => "Description to show below the title"
    ));
    
    $metabox->positional_cropping("Featured Image");
    
  }
  
?>