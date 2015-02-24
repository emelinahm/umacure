<?php

  /* Common Functions */

  add_filter('the_excerpt_rss', 'theme_add_rss_content');
  add_filter('the_content_feed', 'theme_add_rss_content');
  add_filter('media_send_to_editor', 'theme_media_send_to_editor');

  /* Overrides the posts display setting on Settings > Reading */
  
  function override_show_on_front($val) {
    return 'posts';
  }
  
  /* Loads theme-init.php */
  
  function theme_init_callback() {
    // Include theme init file
    include(TEMPLATEPATH . '/includes/theme-init.php');
  }

  /* Loads queued sidebars */

  function widget_init_callback() { global $theme_sidebars;
    // Load sidebars
    foreach ($theme_sidebars as $sidebar) {
      register_sidebar($sidebar);
    }
  }

  /* Prints strings/objects */

  function pre($data, $exit=true, $escape=false) {
    if (is_string($data)) {
      if ($escape) $data = esc_html($data);
      echo "<pre>$data</pre>";
    } else {
      echo '<pre>'; print_r($data); echo '</pre>';
    }
    if ($exit) exit();
  }
  
  /* Prints escaped strings/objects */
  
  function pre_html($data, $exit=true) {
    pre($data, $exit, true);
  }
  
  /* Creates an associative array from a numerical array */
  
  function make_assoc_array($arr, $space_replacer='') {
    $out = array();
    foreach ($arr as $val) {
      if ($space_replacer) {
        $out[preg_replace('/[ ]/', $space_replacer, $val)] = $val;
      } else {
        $out[$val] = $val;
      }
      
    }
    return $out;
  }
  
  /* Generates an id from a string */
  
  function id_from_str($str) {
    $id = strtolower(preg_replace('/([^a-zA-Z0-9 ]+|[\-_]+)/', '', $str));
    $id = preg_replace('/\s+/', '-', $id);
    return $id;
  }
  
  /* Generates an ID from a generic string */
  
  function string2id($string) {
    $string = preg_replace('/[^a-z0-9 _-]/i', '', $string); // Strip all unsafe chars
    $string = preg_replace('/[ _-]+/', '-', $string);       // Convert safe symbols to dashes
    $string = strtolower($string);                          // Convert to lower case
    return $string;
  }

  /* Converts CSV to an array */

  function csv2array ($str, $trim=true) {
  	if ( empty($str) ) {
      return array();
  	} else if (is_array($str)) {
  	  return $str;
  	}
  	
  	$out = explode(',', $trim ? trim($str) : $str);
  	$count = count($out);
  	for ($i=0; $i < $count; $i++) {
  		$out[$i] = $trim ? trim($out[$i]) : $out[$i];
  	}
  	$out = array_filter($out);
  	return $out;
  }

  /* Converts a list to an array */

  function list2array($str) {
    if ( empty($str) ) return array();
    $out = preg_split('/\n+/', trim($str));
    $count = count($out);
  	for ($i=0; $i < $count; $i++) {
  		$out[$i] = trim($out[$i]);
  	}
  	$out = array_filter($out);
  	return $out;
  }

  /* Removes ending '<br />' from a string */

  function remove_br($str, $all=false) {
    $str = trim(preg_replace('/(^<br \/>|^<br\/>|<br \/>$|<br\/>$)/', '', $str));
    if ($all) $str = preg_replace('/<br[ ]?\/>/', '', $str);
    return $str;
  }
  
  /* Plain simple template engine */

  function do_template($tpl, $vars, $escape=false) {
    foreach ($vars as $key => $val) {
      if (is_array($val)) $val = implode(', ', $val);
      $tpl = preg_replace(sprintf('/\{\{%s\}\}/', preg_quote($key, '/')), esc_html($val), $tpl);
    }
    if ($escape) $tpl = esc_html($tpl);
    return $tpl;
  }

  /* Prints a boolean string */

  function bool_to_string($bool) {
    return ($bool) ? 'true' : 'false';
  }
  
  /* Prints a boolean from yes/no */
  
  function str2bool($str) {
    $str = strtolower($str);
    return ($str == 'true' || $str == 'yes' || $str == 'y' || $str == 1);
  }
  
  /* Adds post image to RSS Content */
  
  function theme_add_rss_content($content) { global $der_framework;
    $out = '';
    $img = $der_framework->post_thumb(500, 0);
    if ($img) $out .= sprintf('<p><img alt="%s" src="%s" /></p>'."\n", get_the_title(), $img);
    return $out . $content;
  }
  
  /* Create thumbnail automatically when uploading images */
  
  function theme_media_send_to_editor($html) { global $der_framework;
    preg_match("/href='(.*?)'/", $html, $matches);
    if ($matches) $der_framework->thumb_src($matches[1], GI_THUMB_WIDTH, GI_THUMB_HEIGHT); // Create thumb automatically
    return $html;
  }
  
  /* Prints a google webfonts stylesheet <link> */
  
  function webfont_stylesheet_url($font, $subsets=array(), $variants=array()) {
    $link = "http://fonts.googleapis.com/css?family=" . preg_replace('/\s+/', '+', $font);
    if (!empty($variants)) {
      $arr = array();
      foreach (array_keys($variants) as $key) {
        if (!is_int($key)) $arr[] = preg_replace('/^_/', '', $key);
      }
      $link .= ':' . implode(',', $arr);
    }
    if (!empty($subsets)) {
      $arr = array();
      foreach (array_keys($subsets) as $key) {
        if (!is_int($key)) $arr[] = preg_replace('/^_/', '', $key);
      }
      $link .= '&amp;subset=' . implode(',', $arr);
    }
    return $link;
  }

?>
