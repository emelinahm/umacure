<?php

  add_shortcode('info', 'meteor_notification');
  add_shortcode('tip', 'meteor_notification');
  add_shortcode('warning', 'meteor_notification');
  add_shortcode('success', 'meteor_notification');
  add_shortcode('error', 'meteor_notification');

  function meteor_notification($atts, $content='', $code='') { global $der_framework;

    // Options: dismiss noicon

    $atts = (array) $atts;

    $args = array(
      'icon' => null,
      'dismiss' => false,
      'content' => null,
      'type' => $code
    );
    
    if (in_array('dismiss', $atts)) $args['dismiss'] = true;
    
    if (!in_array('plain', $atts)) {
      switch ($code) {
        case 'info': $args['icon'] = 'icon-info-sign'; break;
        case 'tip': $args['icon'] = 'icon-magic'; break;
        case 'warning': $args['icon'] = 'icon-warning-sign'; break;
        case 'success': $args['icon'] = 'icon-ok'; break;
        case 'error': $args['icon'] = 'icon-minus-sign'; break;
      }
    }

    $args['content'] = $der_framework->content($content, false);

    return $der_framework->render_template('meteor-notification.mustache', $args);

  }

?>