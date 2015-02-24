<?php

  // Social icons path
  if (!defined('SOCIAL_ICONS_PATH')) define('SOCIAL_ICONS_PATH', 'core/images/admin/social-icons');

  function get_theme_social_icons() { global $der_framework;
    $icons = array();
    $files = @scandir(TEMPLATEPATH . '/' . SOCIAL_ICONS_PATH);
    if ($files) {
      $ext_regex = '/\.png$/';
      foreach ($files as $file) {
        if ($file[0] == '.' || ! preg_match($ext_regex, $file)) continue;
        else {
          $icons[preg_replace($ext_regex, '', $file)] = $der_framework->uri(SOCIAL_ICONS_PATH . '/' . $file, THEME_VERSION);
        }
      }
    }
    return $icons;
  }

?>