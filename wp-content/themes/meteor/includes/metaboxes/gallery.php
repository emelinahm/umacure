<?php

  /* Gallery Metabox */
  
  theme_metabox(array(
    'id' => 'gallery-metabox',
    'title' => 'Gallery Configuration',
    'callback' => 'gallery_metabox_callback',
    'post_type' => 'gallery',
  ));

  function gallery_metabox_callback() { global $metabox;
  
    gallery_thumbs_auto_update();
    
    $metabox->set_nonce();
    
    $metabox->textarea(array(
      'key' => 'gallery_images',
      'title' => "",
      'gallery_interface' => array('width'=>GI_THUMB_WIDTH, 'height'=>GI_THUMB_HEIGHT, 'showDescription'=>true)
    ));
    
  }

?>