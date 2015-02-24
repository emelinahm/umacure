<?php

  function meteor_slider_bundle($atts, $content='', $code='') { global $der_framework;
    
    $defaults = array(
      'id' => null
    );
    
    $args = wp_parse_args($atts, $defaults);

    if (empty($args['id'])) return null;

    $args['fullwidth'] = preg_match('/_fullwidth$/', $code);

    switch ($code) {
      case 'cuteslider':
      case 'cuteslider_fullwidth':
        $args['slider'] = 'cuteslider';
        $args['content'] = do_shortcode(sprintf('[cuteslider id="%d"]', $args['id']));
        break;
      case 'layerslider':
      case 'layerslider_fullwidth':
        $args['slider'] = 'layerslider';
        $args['content'] = do_shortcode(sprintf('[layerslider id="%d"]', $args['id']));
        break;
      case 'revslider':
      case 'revslider_fullwidth':
        $args['slider'] = 'revslider';
        $args['content'] = do_shortcode(sprintf('[rev_slider %d]', $args['id']));
        break;
    }

    return $der_framework->render_template('meteor-slider-bundle.mustache', $args);
    
  }

?>