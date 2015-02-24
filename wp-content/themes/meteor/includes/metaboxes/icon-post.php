<?php

  /* Icon Post Metabox */
  
  theme_metabox(array(
    'id' => 'icon-post-metabox',
    'title' => 'Icon Post Details',
    'callback' => 'icon_post_metabox_callback',
    'post_type' => 'icon-post',
  ));
  function icon_post_metabox_callback() { global $der_framework, $metabox;
  
    $metabox->set_nonce();
    
    $metabox->icon(array(
      'key' => 'icon',
      'title' => "Icon",
      'desc' => "Vector icon to use for this post."
    ));
    
    $metabox->text(array(
      'key' => 'description',
      'title' => "Description",
      'desc' => "Description to show below the title (optional)."
    ));
  
    $metabox->text(array(
      'key' => 'url',
      'title' => "URL",
      'desc' => "Link associated with this post (optional)."
    ));
    
    $metabox->text(array(
      'key' => 'order',
      'title' => "Order",
      'desc' => "Position in which this post is displayed."
    ));
    
    $metabox->positional_cropping("Image");
    
  }
  
?>