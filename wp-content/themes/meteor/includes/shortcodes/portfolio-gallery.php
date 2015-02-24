<?php

  add_shortcode('portfolio-gallery', 'meteor_portfolio_gallery_shortcode'); // For backwards compatibility
  add_shortcode('portfolio_gallery', 'meteor_portfolio_gallery_shortcode');

  function meteor_portfolio_gallery_shortcode($atts, $content='', $code='') { global $der_framework;

    $atts = (array) $atts;
    $out = null;
    
    $post = get_post();
    
    if ($post->post_type === 'portfolio' && $der_framework->postmeta('portfolio_display') !== 'portfolio_gallery') {
      
      $args = theme_gallery_postmeta(array(), $der_framework->layout->container_columns);
      
      $args['opt_lightbox'] = true;
      $args['opt_permalink'] = $der_framework->postmeta_bool('gallery_permalink');
      $args['click_behavior'] = 'lightbox';
      
      $out = $der_framework->render_template('meteor-gallery.mustache', $args);

      return $out;
      
    }
    
    return $out;
     
  }
  
?>
