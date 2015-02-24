<?php

  /* Post Metabox */
  
  theme_metabox(array(
    'id' => 'post-metabox',
    'title' => 'Post Details',
    'callback' => 'post_metabox_callback',
    'post_type' => 'post'
  ));

  function post_metabox_callback() { global $der_framework, $metabox, $post;

    $metabox->set_nonce();
    
    $metabox->format('standard');
    
    $post_format = get_post_format($post->ID);
    
    // STANDARD

    $metabox->text(array(
      'key' => 'description',
      'title' => "Description",
      'desc' => "Description to show below the title"
    ));
      
    if ($post_format === false) $metabox->positional_cropping("Post Image");
    
    $metabox->format('/standard', 'quote');
    
    
    // QUOTE
    
    $metabox->text(array(
      'key' => 'quote_author',
      'title' => "Quote Source",
      'desc' => "Author or Source of the quote"
    ));
      
    $metabox->text(array(
      'key' => 'quote_description',
      'title' => "Quote Description",
      'desc' => "Description to add next to the quote source"
    ));
      
    $metabox->text(array(
      'key' => 'quote_url',
      'title' => "URL",
      'desc' => "Author or Quote URL"
    ));
      
    $metabox->format('/quote', 'link');
    
    
    // LINK
    
    $metabox->textarea(array(
      'key' => 'link_content',
      'title' => "Enter your Link(s) Below",
      'desc' => "Links to add"
    ));
      
    $metabox->format('/link', 'audio');
    
    // AUDIO

    $metabox->text(array(
      'key' => 'audio_url',
      'title' => "Audio URL",
      'upload' => "Audio",
      'desc' => 'Specify the Audio URL to use for this post.
<br/>This can be a video from the <u>Media Library</u>, <u>Youtube</u>, <u>Vimeo</u>, or other <a target="_blank" href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F">supported video site</a>.
<br/><br/><i class="icon-info-sign"></i> <a target="_blank" href="http://en.wikipedia.org/wiki/HTML5_video#Browser_support">Browser video support chart</a>'
    ));
    
    if ($post_format == 'audio') $metabox->positional_cropping("Featured Image");
    
    $metabox->format('/audio', 'video');
    
    // VIDEO
    
    $metabox->text(array(
      'key' => 'video_url',
      'title' => "Video URL",
      'upload' => "Video",
      'desc' => 'Specify the Video URL to use for this post.
<br/>This can be a video from the <u>Media Library</u>, <u>Youtube</u>, <u>Vimeo</u>, or other <a target="_blank" href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F">supported video site</a>.
<br/><br/><i class="icon-info-sign"></i> <a target="_blank" href="http://en.wikipedia.org/wiki/HTML5_video#Browser_support">Browser video support chart</a>'
    ));
    
    $metabox->format('/video', 'image');
    
    // IMAGE
    
    $metabox->text(array(
      'key' => 'image_description_str',
      'title' => "Description",
      'desc' => "Description to add to the gallery post."
    ));
    
    $metabox->text(array(
      'key' => 'image_height',
      'title' => "Image Height",
      'desc' => "Height to use for the image thumbnail."
    ));
    
    $metabox->format('/image', 'gallery');
    
    
    // GALLERY
    
    $metabox->textarea(array(
      'key' => 'gallery_images',
      'title' => "",
      'gallery_interface' => array('width'=>GI_THUMB_WIDTH, 'height'=>GI_THUMB_HEIGHT, 'showDescription'=>true)
    ));
      
    $metabox->text(array(
      'key' => 'gallery_description_str',
      'title' => "Gallery Description",
      'desc' => "Description to add to the gallery post."
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
    
    if ($post_format == 'gallery') $metabox->positional_cropping("Gallery Image");
        
    $metabox->text(array(
      'key' => 'gallery_thumb_height',
      'title' => "Thumbnail Height",
      'desc' => "Gallery thumbnail image height."
    ));
      
    $metabox->format('/gallery');
    

  }
  
?>