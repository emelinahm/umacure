<?php

  function component_get_posts($args, $options=array()) { global $der_framework;

    // Additional arguments:  showposts, width, height

    $options = wp_parse_args($options, array(
      'post_type' => 'post',
      'taxonomy' => 'category',
      'custom_query' => null,
      'featured_image' => false,
      'optimize_image_size' => true,
      'default_query' => false,
      'chunks' => true,
      'reset_query' => true,
      'title' => true,
      'excerpt' => false,
      'content' => false,
      'shortcodes' => false,
      'show_description' => false,
      'permalink' => false,
      'edit_post_link' => false,
      'post_class' => true,
      'pagination' => false,
      'meta_keys' => null,
      'metadata_display' => 'description',
      'post_formats' => false
    ));
      
    if ($options['taxonomy'] == 'category') $options['taxonomy'] = 'category_name';

    // Parse mini shortcodes
    if (isset($args['more_text']) && $args['more_text']) {
      $args['more_text'] = theme_mini_shortcode(esc_html($args['more_text']));
    }

    $columns = (isset($args['columns']) && $args['columns']) ? (int) $args['columns'] : null;
    
    if ($columns) {
      $args['columns_class'] = $der_framework->layout->column_class($columns);
    }
  
    // Pagination
  
    $paged = defined('__PAGED__') ? __PAGED__ : get_query_var('paged');
  
    $pagination = ($options['pagination']) ? ($paged ? $paged : 1) : 1;
  
    if ($options['default_query'] == false) {
      
      $custom_query = $options['custom_query'];
      
      if ($custom_query && preg_match('/^[^&]/', $custom_query)) {
        $query = $args['custom_query']; // Replace query
      } else {
        $showposts = (isset($args['showposts']) ? $args['showposts'] : null);
        $category = (isset($args['category']) ? (is_array($args['category']) ? implode(',', $args['category']) : $args['category']) : null);
        $query = sprintf('post_type=%s&%s=%s&showposts=%d&paged=%d', $options['post_type'], $options['taxonomy'], $category, $showposts, $pagination);
        if ($custom_query && preg_match('/^&/', $custom_query)) $query .= $custom_query; // Append to query
      }
      
      $der_framework->query_post_formats = $options['post_formats'];
      
      query_posts($query);

    }
  
    $single_post = (isset($args['single_post']) && $args['single_post']);
  
    if ($single_post) $options['post_class'] = true;
  
    $posts = array();
  
    while (have_posts()) { the_post();
      
      // Loop variables
      $p = get_post(get_the_ID());
      $arr = array('id' => $p->ID);
      if ($options['title']) $arr['title'] = get_the_title();
      $post_type = get_post_type();
      
      if ($post_type == 'portfolio') {
        
        $portfolio_display = $der_framework->postmeta('portfolio_display');
        
        switch ($portfolio_display) {
          case 'featured_image':
            $post_format = $arr['post_format'] = 'standard';
            $arr['icon'] = 'icon-camera-retro';
            break;
          case 'portfolio_gallery':
            $post_format = $arr['post_format'] = 'gallery';
            break;
        }
        
      } else {
        
        $post_format = $arr['post_format'] = get_post_format();
        
        if (empty($post_format)) {
          $post_format = $arr['post_format'] = 'standard';
          $arr['icon'] = 'icon-pencil';
        }
        
      }
      
      if (is_attachment()) $arr['is_attachment'] = true;
      
      // Variables that accept overrides
      $excerpt = $options['excerpt'];
      $content = $options['content'];
      $shortcodes = $options['shortcodes'];
      $featured_image = $options['featured_image'];

      if ($post_format != 'standard') {
        
        // Ensure content shortcodes are not rendered for the post formats that use the original post content.

        $shortcodes = false;
        $featured_image = false;
        
        switch ($post_format) {
          
          case 'audio':
            $featured_image = true;
            $arr['icon'] = 'icon-music';
            $audio = trim($der_framework->postmeta('audio_url'));
            if ($audio) {
              $arr['mobile_icon'] = true;
              $arr['audio_file'] = $audio;
              $info = pathinfo($audio);
              switch (strtolower($info['extension'])) {
                case 'mp1': case 'mp2': case 'mp3': case 'mpg': case 'mpeg': $arr['audio_mimetype'] = 'audio/mpeg'; break;
                case 'mp4': case 'm4a': $arr['audio_mimetype'] = 'audio/mp4'; break;
                case 'ogg': case 'oga': case 'ogg': $arr['audio_mimetype'] = 'audio/ogg'; break;
                case 'wav': $arr['audio_mimetype'] = 'audio/wav'; break;
                case 'webm': $arr['audio_mimetype'] = 'audio/webm'; break;
                default:
                  unset($arr['mobile_icon']);
                  unset($arr['audio_file']);
                  break;
              }
            }
            break;
          
          case 'video':
            global $wp_embed;
            $arr['icon'] = 'icon-film';
            $video = trim($der_framework->postmeta('video_url'));
            if ($video) {
              $arr['has_video'] = true;
              $arr['mobile_icon'] = true;
              $arr['video_id'] = sprintf('wp-video-%d', $p->ID);
              $content = $wp_embed->shortcode(null, $video);
              if (preg_match('/<(iframe|object|embed)/', $content)) {
                // Got online video
                $arr['video_embed'] = $content;
              } else {
                // Got video file
                $arr['video_file'] = $video;
                $info = pathinfo($video);
                switch (strtolower($info['extension'])) {
                  case 'mov': $arr['video_mimetype'] = 'video/quicktime'; break;
                  case 'mp4': $arr['video_mimetype'] = 'video/mp4'; break;
                  case 'ogv': $arr['video_mimetype'] = 'video/ogg'; break;
                  case 'avi': $arr['video_mimetype'] = 'video/x-msvideo'; break;
                  case 'wmv': $arr['video_mimetype'] = 'video/x-ms-wmv'; break;
                  case 'flv': $arr['video_mimetype'] = 'video/x-flv'; break;
                  case 'm3u8': $arr['video_mimetype'] = 'application/x-mpegURL'; break;
                  case 'ts': $arr['video_mimetype'] = 'video/MP2T'; break;
                  case '3gp': $arr['video_mimetype'] = 'video/3gpp'; break;
                  case '3g2': $arr['video_mimetype'] = 'video/3gpp2'; break;
                  case 'webm': $arr['video_mimetype'] = 'video/webm'; break;
                  default:
                    unset($arr['has_video']);
                    unset($arr['video_file']);
                    unset($arr['mobile_icon']);
                    break;
                }
              }
            }
            break;

          case 'chat':
            $excerpt = false;
            $content = true;
            break;

          case 'aside':
            $excerpt = false;
            $content = true;
            break;

          case 'quote':
            $excerpt = false;
            $content = true;
            $options['meta_keys'] = array('quote_author', 'quote_description', 'quote_url');
            break;

          case 'link':
            $options['meta_keys'] = array('link_content');
            break;

          case 'status':
            $excerpt = false;
            $content = true;
            $arr['avatar'] = get_avatar(get_the_author_meta('ID'), 64);
            $arr['user_url'] = get_the_author_meta('user_url');
            break;

          case 'image':
            $arr['icon'] = "icon-camera-retro";
            $shortcodes = $options['shortcodes'];
            $featured_image = true;
            break;

          case 'gallery':
            $excerpt = true;
            $featured_image = true;
            if ($options['post_formats'] === true) {
              $arr = theme_gallery_postmeta($arr, $args['slots']);
            }
            break;

        }
        
      }
      
      if ($options['post_formats']) {
        $arr['post_format'] = $post_format;
        $arr['is_' . $post_format] = true;
      }
      
      if (!empty($args['display_options'])) {
        $metadata = theme_metadata_html($args['display_options'], true);
        $arr['metadata'] = $metadata['metadata'];
        $arr['metadata_tags'] = $metadata['metadata_tags'];
        $arr['metadata_all'] = $metadata['metadata_all'];
      }
    
      if ($options['permalink']) $arr['permalink'] = get_permalink();
      
      if ($options["show_description"]) $arr['description'] = $der_framework->metadata_display($options['metadata_display']);
      if ($options["post_class"]) $arr['post_class'] = implode(' ', get_post_class());
      if ($excerpt) $arr['excerpt'] = $der_framework->excerpt();
      if ($content) $arr['content'] = $der_framework->content($p->post_content, $shortcodes);
      
      $edit_str = __("Edit %s", "theme");
      
      $edit_post_link = ($options['edit_post_link'] && $der_framework->option_bool('show_edit_post_links') && is_user_logged_in());
      
      if ($edit_post_link) {

        switch ($post_type) {
          case 'post':
          case 'portfolio':
            $can_edit = current_user_can('edit_posts');
            break;
          case 'page':
            $can_edit = current_user_can('edit_pages');
            break;
          case 'attachment':
            $can_edit = current_user_can('upload_files');
            break;
          default:
            $can_edit = false;
            break;
        }

      }
      
      if ($edit_post_link && $can_edit) {
        if (is_page()) {
          $label = sprintf($edit_str, __("Page", "theme"));
        } else if (is_attachment()) {
          $label = sprintf($edit_str, __("Attachment", "theme"));
        } else {
          switch ($post_format) {
            case 'aside':
              $label = sprintf($edit_str, __("Aside", "theme"));
              break;
            case 'gallery':
              $label = sprintf($edit_str, __("Gallery", "theme"));
              break;
            case 'link':
              $label = sprintf($edit_str, __("Link", "theme"));
              break;
            case 'image':
              $label = sprintf($edit_str, __("Image", "theme"));
              break;
            case 'quote':
              $label = sprintf($edit_str, __("Quote", "theme"));
              break;
            case 'status':
              $label = sprintf($edit_str, __("Status", "theme"));
              break;
            case 'video':
              $label = sprintf($edit_str, __("Video", "theme"));
              break;
            case 'audio':
              $label = sprintf($edit_str, __("Audio", "theme"));
              break;
            case 'chat':
              $label = sprintf($edit_str, __("Chat", "theme"));
              break;
            default:
              if ($post_type == 'post') {
                $label = sprintf($edit_str, __("Post", "theme"));
              } else {
                $label = sprintf($edit_str, __("Portfolio Post", "theme"));
              }
              break;
          }
        }
        
        $arr['edit_post_link'] = sprintf('<p class="post-edit-link-container"><a class="post-edit-link notd small" href="%s"><i class="icon-edit"></i>&nbsp; <span>%s</span></a></p>', get_edit_post_link(), $label);
        $arr['excerpt_or_editlink'] = true;
        
      }
      
      if (isset($arr['excerpt']) && !empty($arr['excerpt'])) {
        $arr['excerpt_or_editlink'] = true;
      }
      
      if ($single_post) {
        $arr['excerpt_or_editlink'] = true;
        if (isset($args['visibility']) && $args['visibility']) {
          $arr['post_class'] .= sprintf(" %s", $args['visibility']);
        }
        $arr['post_class'] .= ' single-post';
      }
      
      if ($featured_image) {
        
        $resolution = (int) $der_framework->option('thumb_resolution');
        
        $image = $arr['image_src'] = $der_framework->post_image();

        if (empty($image) && (($post_format == 'gallery' && !$options['post_formats']) || (!is_single() && $post_type == 'portfolio')) ) {
          $gallery_images = (array) json_decode($der_framework->postmeta('gallery_images'), true);
          if (!empty($gallery_images)) {
            $image = $arr['image_src'] = isset($gallery_images[0]['image']) ? $gallery_images[0]['image'] : null;
          }
        }
        
        if (!empty($image)) {
          
          if ($options['post_formats'] && $post_format == 'image') {
            
            $width = $der_framework->layout->get_column_width($args['slots']);
            $height = (int) $der_framework->postmeta('image_height');
            if ($height === 0) $height = $width*GOLDEN_RATIO_FACTOR;
            $arr['image'] = $der_framework->post_thumb($resolution*$width, $resolution*$height);
            
          } else {

            $min_width = 440;
            $thumb_crop = $der_framework->postmeta('thumb_crop');
            
            if ($options['optimize_image_size'] && $args['width'] < $min_width) {
              // Ensure good quality images on mobile resolution
              $width = $resolution*$min_width;
              $height = $resolution*floor(($min_width*$args['height'])/$args['width']);
              $arr['image'] = $der_framework->thumb_src($image, $width, $height, $thumb_crop);
            } else {
              $arr['image'] = $der_framework->thumb_src($image, $resolution*$args['width'], $resolution*$args['height'], $thumb_crop);
            }
          
          }
          
        }
        
      }
    
      if (is_array($options["meta_keys"])) {
        foreach ($options["meta_keys"] as $key) {
          $arr['meta_' . $key] = $der_framework->postmeta($key);
        }
      }

      switch ($post_format) {
        case 'link':
          $content = $der_framework->content($arr['meta_link_content'], false); // no shortcodes
          $arr['meta_link_content'] = make_clickable($content);
          break;
      }
      
      if ($post_type == 'pricing') {
        unset($arr['icon']);
        unset($arr['post_format']);
        $arr['title'] = theme_mini_shortcode(esc_html($arr['title']));
      }
      
      $posts[] = $arr;

    }

    if ($columns && $options["chunks"]) {
      $rows = array_chunk($posts, floor($args['slots']/$columns)); // Grid size is from parent
      foreach ($rows as $k => $v) {
        $rows[$k] = array('posts' => $v);
      }
      $args['rows'] = $rows;
    } else {
      $args['posts'] = $posts;
    }
    
    if ($options['pagination']) {
      $der_framework->last_query = $GLOBALS['wp_query']; // Set last query, for pagination
    }
  
    if ($options["reset_query"]) wp_reset_query();
  
    return $args;

  }

?>
