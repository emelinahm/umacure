<?php

  function theme_gallery_postmeta($arr=array(), $slots) { global $der_framework;
    
    $gallery_images = (array) json_decode($der_framework->postmeta('gallery_images'), true);
    
    if (count($gallery_images) > 0) {
      $thumb_crop = $der_framework->postmeta('thumb_crop');
      $gallery_columns = (int) $der_framework->postmeta('gallery_columns');
      $img_width = floor($der_framework->layout->get_column_width($slots)/$gallery_columns);
      $img_height = (int) $der_framework->postmeta('gallery_thumb_height');
      if ($img_height === 0) $img_height = floor($img_width*GOLDEN_RATIO_FACTOR);
      $resolution = (int) $der_framework->option('thumb_resolution');
      $img_width *= $resolution;
      $img_height *= $resolution;
      foreach ($gallery_images as $i => $item) {
        $gallery_images[$i]['image_src'] = $item['image'];
        $gallery_images[$i]['image'] = $der_framework->thumb_src($item['image'], $img_width, $img_height, $thumb_crop);
      }
      $gallery_images = array_chunk($gallery_images, $gallery_columns);
      foreach ($gallery_images as $row) {
        $images[] = array('items' => $row);
      }
      $arr['icon'] = "icon-picture";
      $arr['gallery_columns'] = floor(LAYOUT_GRID_SIZE/$gallery_columns);
      $arr['gallery_title'] = $der_framework->postmeta_bool('gallery_title');
      $arr['gallery_title_align'] = $der_framework->postmeta('gallery_title_align');
      $arr['gallery_title_icon'] = $der_framework->postmeta('gallery_title_icon');
      $arr['opt_permalink'] = $der_framework->postmeta_bool('gallery_permalink');
      $arr['gallery_description'] = $der_framework->postmeta_bool('gallery_description');
      $arr['show_meta'] = $arr['gallery_title'] || $arr['gallery_description'];
      $arr['rows'] = $images;
      $arr['mobile_icon'] = true;
    }
    
    return $arr;
    
  }

  function theme_gallery_standalone($arr=array(), $args) { global $der_framework;

    $defaults = array(
      'id' => null,
      'slots' => null,
      'limit' => null,
      'thumb_crop' => 'c',
      'columns' => 3,
      'thumb_height' => null,
      'show_title' => true,
      'title_align' => 'left',
      'title_icon' => null,
      'show_description' => true,
      'randomize' => false
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $gallery_images = json_decode(get_post_meta($args['id'], $der_framework->key('gallery_images'), true), true);
    
    if (isset($args['limit'])) $gallery_images = array_slice($gallery_images, 0, $args['limit']);
    
    $slots = $args['slots'];
    
    if (count($gallery_images) > 0) {
      $thumb_crop = $args['thumb_crop'];
      $gallery_columns = (int) $args['columns'];
      $img_width = floor($der_framework->layout->get_column_width($slots)/$gallery_columns);
      $img_height = (int) $args['thumb_height'];
      if ($img_height === 0) $img_height = floor($img_width*GOLDEN_RATIO_FACTOR);
      $resolution = (int) $der_framework->option('thumb_resolution');
      $img_width *= $resolution;
      $img_height *= $resolution;
      foreach ($gallery_images as $i => $item) {
        $gallery_images[$i]['image_src'] = $item['image'];
        $gallery_images[$i]['image'] = $der_framework->thumb_src($item['image'], $img_width, $img_height, $thumb_crop);
      }
      $gallery_images = array_chunk($gallery_images, $gallery_columns);
      foreach ($gallery_images as $row) {
        $images[] = array('items' => $row);
      }
      $arr['icon'] = "icon-picture";
      $arr['gallery_columns'] = floor(LAYOUT_GRID_SIZE/$gallery_columns);
      $arr['gallery_title'] = $args['show_title'];
      $arr['gallery_title_align'] = $args['title_align'];
      $arr['gallery_title_icon'] = $args['title_icon'];
      $arr['gallery_description'] = $args['show_description'];
      $arr['show_meta'] = $args['show_title'] || $args['show_description'];
      $arr['rows'] = $images;
      $arr['mobile_icon'] = true;
    }
    
    return $arr;
    
  }

  function theme_get_gallery_images($args) { global $der_framework;
  
    $out = array();

    $limit = $args['image_limit'];
    
    $nocrop = (isset($args['nocrop']) && $args['nocrop'] === true);
    $crop = !$nocrop;
    
    $columns = (isset($args['columns'])) ? $args['columns'] : null;
    
    if ($limit > 0 && isset($args['gallery']) && $args['gallery']) {
      
      ////////////////////// GALLERY POSTS
    
      $images = json_decode(get_post_meta($args['gallery'], $der_framework->key('gallery_images'), true), true);

      if (is_array($images) && !empty($images)) {
      
        $images = array_slice($images, 0, $limit);
      
        foreach ($images as $i => $img) {
          $image = $img['image'];
          if ($crop) $images[$i]['image'] = ($image) ? $der_framework->thumb_src($image, $args['width'], $args['height']) : null;
          $images[$i]['image_src'] = ($image) ? $image : null;
        }
      
        $limit -= count($images);

        $out = array_merge($out, $images);
      
        // if ($limit == 0) return ($columns) ? theme_gallery_chunkify($out, $columns) : $out;
      
      }

    }
    
    if ($limit > 0 && isset($args['portfolio-category']) && is_array($args['portfolio-category']) && !empty($args['portfolio-category'])) {
    
      ////////////////////// PORTFOLIO POSTS
    
      $cats = implode(',', $args['portfolio-category']);
      $query = sprintf('post_type=portfolio&portfolio-category=%s&showposts=%d', $cats, $limit);
      $posts = query_posts($query); 
    
      while (have_posts()) { the_post(); // title, description, image, url
      
        $post_image = $der_framework->post_image();
      
        $data = array(
          'title' => get_the_title(),
          'description' => $der_framework->postmeta('description'),
          'image_src' => ($post_image) ? $post_image : null,
          'url' => get_permalink()
        );
        
        if ($crop) {
          $data['image'] = ($post_image) ? $der_framework->post_thumb($args['width'], $args['height']) : null;
        } else {
          $data['image'] = $data['image_src'];
        }
        
        $out[] = $data;

      }
    
      wp_reset_query();
    
      $limit -= count($posts);
    
      // if ($limit == 0) return ($columns) ? theme_gallery_chunkify($out, $columns) : $out;
    
    }

    ///////////////////////// POSTS

    if ($limit > 0 && isset($args['category']) && is_array($args['category']) && !empty($args['category'])) {
    
      $cats = implode(',', $args['category']);
      $query = sprintf('post_type=post&category_name=%s&showposts=%d', $cats, $limit);
      $posts = query_posts($query);
    
      while (have_posts()) { the_post();
      
        $post_image = $der_framework->post_image();
      
        $data = array(
          'title' => get_the_title(),
          'description' => $der_framework->postmeta('description'),
          'image_src' => ($post_image) ? $post_image : null,
          'url' => get_permalink()
        );
        
        if ($crop) {
          $data['image'] = ($post_image) ? $der_framework->post_thumb($args['width'], $args['height']) : null;
        } else {
          $data['image'] = $data['image_src'];
        }
      
        $out[] = $data;
        
      }
    
      wp_reset_query();
    
      $limit -= count($posts);
    
      // if ($limit == 0) return ($columns) ? theme_gallery_chunkify($out, $columns) : $out;
    
    }
    
    if ($limit > 0 && isset($args['extra-sources']) && is_array($args['extra-sources'])) {
    
      ////////////////////// PORTFOLIO GALLERY
    
      if (is_single() && get_post_type() == 'portfolio' && in_array('portfolio-gallery', $args['extra-sources'])) {
      
        $images = json_decode($der_framework->postmeta('gallery_images'), true);
        
        $thumb_crop = $der_framework->postmeta('thumb_crop');
        
        if (is_array($images) && !empty($images)) {
        
          $post_image = $der_framework->post_image();

          if ($post_image) {
            // Append post image to gallery images
            $images = array_merge(array(
              array(
                'title' => get_the_title(),
                'description' => $der_framework->postmeta('description'),
                'image' => $post_image ? $der_framework->post_thumb($args['width'], $args['height']) : null,
                'image_src' => $post_image,
                'url' => get_permalink()
              )
            ), $images);
          }
        
          $images = array_slice($images, 0, $limit);
        
          foreach ($images as $i => $img) {
            if ($i === 0) $continue; // Image already processed
            $image = $img['image'];
            if ($crop) $images[$i]['image'] = ($image) ? $der_framework->thumb_src($image, $args['width'], $args['height'], $thumb_crop) : null;
            $images[$i]['image_src'] = ($image) ? $image : null;
          }

          $limit -= count($images);

          $out = array_merge($out, $images);

          // if ($limit == 0) return ($columns) ? theme_gallery_chunkify($out, $columns) : $out;
        
        }
      
      }
    
    }
    
    // Randomize if enabled
    if (isset($args['randomize']) && $args['randomize']) {
      shuffle($out);
    }
    
    if ($columns && isset($args['chunks']) && $args['chunks']) {
      $rows = array_chunk($out, floor($args['slots']/$columns)); // Grid size is from parent
      foreach ($rows as $k => $v) {
        $rows[$k] = array('items' => $v);
      }
      return $rows;
    } else {
      return $out;
    }

  }

?>