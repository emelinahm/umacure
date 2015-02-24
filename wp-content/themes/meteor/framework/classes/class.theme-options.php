<?php

class ThemeOptions {
  
  private $layout_keys;
  
  function __construct() { global $der_framework, $theme_options;
    
    include($der_framework->path('framework/theme-options/theme-options-interface.head.php'));

    foreach ($theme_options as $section => $options) {

      $icon = (isset($options['icon'])) ? $options['icon'] : null;
      
      if ($icon) $icon = sprintf(' data-icon="%s"', $icon);
      
      unset($options['icon']);
      
      $section_id = id_from_str($section);
      
      $defaults = array(
        'type' => null,
        'id' => null,
        'default' => null,
        'description' => null,
        'values' => null,
        'pre_html' => null,
        'post_html' => null
      );
      
      printf("\n".'<div class="section" id="section-%s">
      
<h2 %sclass="title">%s</h2>', $section_id, $icon, $section);
      
      do_action('theme_options_render_head');
      
      foreach ($options as $title => $args) { extract(wp_parse_args($args, $defaults));
         
        if (empty($id) && empty($type)) die(sprintf('Broken Option: %s', $title));
        
        printf("\n\n".'<div class="option option-%s">', $type);
        if (!empty($pre_html)) printf("\n".'<div class="pre-html">%s%s%s</div><!-- .pre-html -->', "\n", $pre_html, "\n");
        
        echo "\n".'<div class="container">';
        echo "\n".'<div class="input-data">';
        printf("\n".'<label class="option-title" for="option-%s">%s</label>', $id, $title);
        
        $context_key = $der_framework->context_key();
        $method = $args['type'];
        $val = $der_framework->option($id, $default);
        
        switch ($method) {
          case 'message':
          case 'text':
          case 'color':
          case 'textarea':
          case 'upload':
          case 'layout':
          case 'stylesheet':
          case 'code':
            $this->$type($args, $id, $val, $context_key);
            break;
          case 'select':
          case 'radio':
          case 'checkbox':
            $this->choice_gen($args, $id, $val, $context_key);
            break;
          case 'taxonomies_select':
            $this->taxonomies_gen('select', $args, $id, $val, $context_key);
            break;
          case 'taxonomies_radio':
            $this->taxonomies_gen('radio', $args, $id, $val, $context_key);
            break;
          case 'taxonomies_checkbox':
            $this->taxonomies_gen('checkbox', $args, $id, $val, $context_key);
            break;
          case 'posts_select':
            $this->posts_gen('select', $args, $id, $val, $context_key);
            break;
          case 'posts_radio':
            $this->posts_gen('radio', $args, $id, $val, $context_key);
            break;
          case 'posts_checkbox':
            $this->posts_gen('checkbox', $args, $id, $val, $context_key);
            break;
          default: break;
        }
        
        echo "\n".'</div><!-- input-data-->';
        echo "\n".'<div class="description">'."\n";

        if (!empty($description)) echo apply_filters('the_excerpt', $description);
        
        echo "\n</div><!-- description -->";
        echo "\n".'<div class="clear"></div>';
        echo "\n</div><!-- .container -->";
        
        if (!empty($post_html)) printf("\n".'<div class="post-html">%s%s%s</div><!-- .post-html -->', "\n", $post_html, "\n");
        printf("\n</div><!-- .option-%s -->", $type);
        
      }
      
      printf("\n\n</div><!-- section-%s -->\n", $section_id);
      
    }
    
    include($der_framework->path('framework/theme-options/theme-options-interface.foot.php'));
    
  }
  
  /* Renders an HTML note */
  
  function message($args) {
    printf('<div class="option">%s</div>', apply_filters('the_excerpt', $args['content']));
  }
  
  /* Renders a text input */
  
  function text($args, $key, $val, $ckey) {
    $this->input_gen($args, $key, $val, $ckey);
  }
  
  /* Renders a color input */
  
  function color($args, $key, $val, $ckey) {
    $args['type'] = 'text';
    $args['color'] = true;
    $this->text($args, $key, $val, $ckey);
  }
  
  /* Renders a textarea */
  
  function textarea($args, $key, $val, $ckey) {
    $this->input_gen($args, $key, $val, $ckey);
  }
  
  /* Renders a code option textarea */
  
  function code($args, $id, $val, $ckey) {
    $args['code'] = true;
    $args['type'] = 'textarea';
    $this->textarea($args, $id, $val, $ckey);
  }
  
  /* Renders an input option */
  
  private function input_gen($args, $id, $val, $ckey) {
    $type = $args['type'];
    switch ($type) {
      case 'text':
        if (isset($args['color']) && $args['color']) {
          printf("\n".'<p><input class="color" data-default-color="%s" type="text" id="option-%s" name="%s[%s]" autocomplete="off" tabindex="1" value="%s" /><span class="color-box"></span></p>', $args['default'], $id, $ckey, $id, $val);
        } else {
          printf("\n".'<p><input type="text" id="option-%s" name="%s[%s]" autocomplete="off" tabindex="1" value="%s" /></p>', $id, $ckey, $id, $val);
        }
        break;
      case 'textarea';
        if (!isset($args['rows'])) $args['rows'] = 4;
        if (!isset($args['cols'])) $args['cols'] = 80;
        if (isset($args['code']) && $args['code']) {
          printf("\n".'<p><textarea class="code-entry" id="option-%s" name="%s[%s]" autocomplete="off" rows="%d" cols="%d" tabindex="1">%s</textarea></p>', $id, $ckey, $id, $args['rows'], $args['cols'], $val);
        } else {
          printf("\n".'<p><textarea id="option-%s" name="%s[%s]" autocomplete="off" rows="%d" cols="%d" tabindex="1">%s</textarea></p>', $id, $ckey, $id, $args['rows'], $args['cols'], $val);
        }
        break;
    }
  }
  
  /* Renders a stylesheet select input */
  
  function stylesheet($args, $id, $val, $ckey) { global $der_framework;
    $args['type'] = 'select';
    $files = scandir($der_framework->path('core/styles'));
    $values = array();
    foreach ($files as $file) {
      if (preg_match('/\.css/i', $file)) {
        $values[$file] = $file;
      }
    }
    $args['values'] = $values;
    $this->choice_gen($args, $id, $val, $ckey);
  }
  
  /* Renders Radio / Select inputs */
  
  function choice_gen($args, $id, $val, $ckey) { global $der_framework;
    
    $type = $args['type'];
    $values_src = isset($args['values']) ? $args['values'] : null;
    $trim = isset($args['notrim']) ? !$args['notrim'] : true;
    
    if (is_string($values_src)) {

      // Process CSV for Select Options
      
      $values = csv2array($values_src, $trim);
      
      if (empty($val)) {
        if ($type == 'checkbox') {
          $val = array();
        } else {
          $val = ($args['first_empty']) ? null : $values[0];
        }
      }
      
      // Create an array having the same keys/values
      $values = make_assoc_array($values);
      
    } else if (is_array($values_src)) {
      
      // Process Array for Select Options
      
      $values = $values_src;

      if ($type == 'radio' || $type == 'select') {
        if ($val) {
          $val = isset($values[$val]) ? $values[$val] : null;
        } else {
          $keys = array_keys($values);
          if (!empty($keys)) $val = $values[$keys[0]];
        }
      }

    } else {
      $values = array();
    }
    
    if ($type == 'checkbox' && is_string($val)) {
      // Checkbox values are always of array type
      $val = csv2array($val, $trim);
    }

    if ($type == 'select') {
      
      if (isset($args['escape']) && $args['escape']) {
        $val = esc_html($val);
      }
      
      if (isset($args['update_fonts']) && $args['update_fonts']) {
        printf("\n".'<p><select class="update-fonts" data-active="%s" id="option-%s" name="%s[%s]" tabindex="1">', $val, $id, $ckey, $id);
      } else {
        printf("\n".'<p><select id="option-%s" name="%s[%s]" tabindex="1">', $id, $ckey, $id);
      }
      
      if (isset($args['first_empty']) && $args['first_empty']) {
        printf("\n".'<option value=" "> </option>');
      }
      foreach ($values as $key => $option) {
        if ($option == $val) {
          printf("\n".'<option selected="selected" value="%s">%s</option>', $key, $option);
        } else {
          printf("\n".'<option value="%s">%s</option>', $key, $option);
        }
      }
    } else if ($type == 'radio') {
      
      foreach ($values as $key => $option) {
        if ($option == $val) {
          printf("\n".'<p><label><input type="radio" checked="checked" name="%s[%s]" value="%s" /> %s</label></p>', $ckey, $id, $key, $option);
        } else {
          printf("\n".'<p><label><input type="radio" name="%s[%s]" value="%s" /> %s</label></p>', $ckey, $id, $key, $option);
        }
      }
      
    } else if ($type == 'checkbox') {
      if ($val) printf('<span class="values" data-values="%s"></span>', esc_html(json_encode(array_keys($val))));
      foreach ($values as $key => $option) {
        if ($val[$key] === 'on') {
          printf("\n".'<p><label><input type="checkbox" checked="checked" name="%s[%s][%s]" /> %s</label></p>', $ckey, $id, $key, $option);
        } else {
          printf("\n".'<p><label><input type="checkbox" name="%s[%s][%s]" /> %s</label></p>', $ckey, $id, $key, $option);
        }
      }
      
    }
    
    if ($type == 'select') echo "\n</select></p>";
  }
  
  /* Gets posts */
  
  private function get_posts($post_type) {
    $query = new WP_Query(sprintf('post_type=%s&showposts=-1', $post_type));
    $values = array();
    foreach ($query->posts as $post) {
      $values[$post->ID] = esc_html($post->post_title);
    }
    return $values;
  }
  
  /* Renders taxonomies inputs */
  
  function taxonomies_gen($type, $args, $id, $val, $ckey) { global $der_framework;
    $args['type'] = $type;
    $args['values'] = $der_framework->get_taxonomies($args['taxonomy'], false); // Retrieve by id instead of slug
    unset($args['taxonomy']);
    $this->choice_gen($args, $id, $val, $ckey);
  }
  
  /* Renders posts inputs */
  
  function posts_gen($type, $args, $id, $val, $ckey) {
    $args['type'] = $type;
    $args['values'] = $this->get_posts($args['post_type']);
    unset($args['post_type']);
    $this->choice_gen($args, $id, $val, $ckey);
  }
  
  /* Renders an upload box */
  
  function upload($args, $id, $val, $ckey) { global $der_framework;
     printf("\n".'<p><input class="upload-image-input" type="text" id="option-%s" name="%s[%s]" autocomplete="off" tabindex="1" value="%s" />
<a rel="lightbox" class="image-thumbnail" href=""><img alt="" src="" /></a></p>', $id, $ckey, $id, $val);
  }
  
  /* Renders a layout selection box */
  
  function layout($args, $id, $val, $ckey) { global $der_framework;
    $args['type'] = 'select';
    $args['values'] = $der_framework->layout_keys;
    $args['first_empty'] = true;
    $args['notrim'] = true;
    $args['escape'] = true;
    $this->choice_gen($args, $id, $val, $ckey);
  }
  
}

?>