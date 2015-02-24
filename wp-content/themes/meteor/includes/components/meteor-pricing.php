<?php

  function meteor_pricing($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'category' => null,
      'showposts' => 4,
      'columns' => 3,
      'money_symbol' => '$',
      'button_class' => 'medium',
      'button_text' => __("Purchase Now", "theme"),
      'icon' => null,
      'text_align' => 'center'
    );
    
    $args = wp_parse_args($atts, $defaults);

    $args['button_text'] = theme_mini_shortcode($args['button_text']);
    
    $args = component_get_posts($args, array(
      'post_type' => 'pricing',
      'taxonomy' => 'pricing-category',
      'chunks' => true,
      'post_class' => false,
      'content' => true,
      'shortcodes' => false,
      'post_formats' => false,
      'showposts' => $args['showposts'],
      'meta_keys' => array('price', 'method', 'url', 'active')
    ));
      
    $rows = $args['rows'];

    // Three foreaches, cool huh ?
    foreach ($rows as $i => $chunk) {
      foreach ($chunk as $key => $posts) {
        foreach ($posts as $j => $post) {
          $rows[$i][$key][$j]['meta_active'] = ($post['meta_active'] == 'yes');
          $content = strip_tags($post['content'], '<a><strong><u><em><span><del><i>');
          $content = preg_replace("/\n+/", "\n", $content);
          $content = (array) explode("\n", $content);
          $content = array_filter($content);
          $out = array();
          foreach ($content as $item): $out[] = array('feature' => theme_mini_shortcode($item)); endforeach;
          $rows[$i][$key][$j]['content'] = $out;
        }
      }
    }

    $args['rows'] = $rows;
    
    return $der_framework->render_template('meteor-pricing.mustache', $args);
    
  }

?>
