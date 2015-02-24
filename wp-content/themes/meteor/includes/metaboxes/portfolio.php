<?php

  /* Portfolio Metabox */
  
  theme_metabox(array(
    'id' => 'portfolio-metabox',
    'title' => 'Portfolio Post Details',
    'callback' => 'portfolio_metabox_callback',
    'post_type' => 'portfolio',
  ));

  function portfolio_metabox_callback() { global $metabox;
  
    gallery_thumbs_auto_update();
  
    $metabox->set_nonce();
    
    $metabox->text(array(
      'key' => 'description',
      'title' => "Description",
      'desc' => "Description to show below the title"
    ));
      
    $metabox->select(array(
      'key' => 'portfolio_display',
      'title' => "Portfolio Display",
      'width' => "200",
      'desc' => "Determines what to display by default for this portfolio post.",
      'default' => "featured_image",
      'options' => array(
        'featured_image' => "Featured Image",
        'portfolio_gallery' => "Portfolio Gallery"
      )
    ));
    
    $metabox->textarea(array(
      'key' => 'gallery_images',
      'title' => "Gallery Images",
      'desc' => "Images to use in the portfolio gallery. Each line should have an image url.
        
<br/><br/>You can embed the gallery into the post using the following <strong>shortcode:</strong> &nbsp; <code>[portfolio_gallery]</code>",
      'rows' => 6,
      'gallery_interface' => array('width'=>GI_THUMB_WIDTH, 'height'=>GI_THUMB_HEIGHT, 'showDescription'=>true)
    ));
      
    $metabox->select(array(
      'key' => 'gallery_columns',
      'title' => "Gallery Columns",
      'width' => "100",
      'desc' => "Number of columns to display the gallery in.",
      'default' => "3",
      'options' => array(
        '1' => "1",
        '2' => "2",
        '3' => "3",
        '4' => "4"
      )
    ));
        
    $metabox->select(array(
      'key' => 'gallery_title',
      'title' => "Show Title",
      'width' => "100",
      'desc' => "Determines if the title for each of the images is shown.",
      'default' => "yes",
      'options' => array(
        'yes' => "Yes",
        'no' => "No"
      )
    ));
    
    $metabox->select(array(
      'key' => 'gallery_title_align',
      'title' => "Gallery Title Alignment",
      'width' => "100",
      'desc' => "The alignment to use for the gallery item titles.",
      'default' => "left",
      'options' => array(
        'left' => "Left",
        'center' => "Center",
        'right' => "Right"
      )
    ));
        
    $metabox->icon(array(
      'key' => 'gallery_title_icon',
      'title' => "Gallery Title Icon",
      'desc' => "Vector icon to add next to the gallery title."
    ));
      
    $metabox->select(array(
      'key' => 'gallery_permalink',
      'title' => "Show Permalink Button",
      'width' => "100",
      'desc' => "Determines if the gallery thumbnails will display a permalink button.",
      'default' => "no",
      'options' => array(
        'yes' => "Yes",
        'no' => "No"
      )
    ));
    
    $metabox->select(array(
      'key' => 'gallery_description',
      'title' => "Show Description",
      'width' => "100",
      'desc' => "Determines if the description for each of the images is shown.",
      'default' => "yes",
      'options' => array(
        'yes' => "Yes",
        'no' => "No"
      )
    ));
      
    $metabox->positional_cropping("Gallery Image");
        
    $metabox->text(array(
      'key' => 'gallery_thumb_height',
      'title' => "Thumbnail Height",
      'desc' => "Gallery thumbnail image height."
    ));
    
  }
  
?>