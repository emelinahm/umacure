<?php

// Positional Cropping meta key
if (!defined('THUMB_CROP_META')) define('THUMB_CROP_META', 'thumb_crop');

class Metabox {
  
  var $prefix;
  var $nonce;
  var $keys = array();
  var $opened = false;
  
  /* Constructor */
  
  function __construct() { global $der_framework;
    // Set post meta prefix
    $this->prefix = $der_framework->prefix;
  }
  
  /* Sets the metabox for a specific format */
  
  function format($format, $recursive=null) {
    if (!$this->is_lookup_mode()) {
      $open = preg_match('/^[a-z]/', $format);
      $format = $format_str = preg_replace('/^[^a-z]/', '', $format);
      if ($format == 'standard') $format = false;
      if (get_post_format() != $format) {
        echo ($open) ? sprintf("<div data-format=\"%s\" style=\"display: none !important;\">", $format_str) : sprintf("\n</div><!-- hide-format-%s -->", $format_str);
      } else {
        echo ($open) ? sprintf("<div data-format=\"%s\" data-metabox-active=\"true\">\n", $format_str) : sprintf("\n</div><!-- show-format-%s -->", $format_str);
      }
      if ($recursive) $this->format($recursive);
    }
  }
  
  /* 
   * Initialize Nonce 
   *
   * IMPORTANT: This function needs to be called on the metabox
   * callback, otherwise the settings will not be saved, since it
   * won't be possible to verify the nonce.
   */
  
  function set_nonce() { global $der_framework;
    if ( ! $this->is_lookup_mode() ) {
      $der_framework->nonce();
    }
  }
  
  /* Check if running in lookup mode */
  
  private function is_lookup_mode() {
    return (defined('METABOX_LOOKUP_MODE') && METABOX_LOOKUP_MODE);
  }
  
  /* Renders HTML Code */
  
  function html($args) {
    
    if ( $this->is_lookup_mode() ) return; // Return on lookup mode
    
    $defaults = array(
      'msg' => ''
    );
    
    $args = wp_parse_args($args, $defaults);
    extract($args);
    
    printf("\n" . '<div class="metabox-entry">
%s
</div><!-- .metabox-entry -->' . "\n", $msg);

  }
  
  /* Renders a Text Input */
  
  function text($args) { global $der_framework, $metabox_queue;
    
    $post_id = get_the_ID();
    
    $defaults = array(
      'key' => null,
      'title' => '',
      'upload' => false,
      'desc' => ''
    );
    
    $args = wp_parse_args($args, $defaults);
    extract($args);
    
    $key = $this->prefix . $key;
    
    // Register key & exit on lookup mode
    if ($this->is_lookup_mode()) {
      $this->keys[] = $key; return;
    }
    
    $val = get_post_meta($post_id, $key, true);
    
    $vars = array(
      'key' => $key,
      'title' => $title,
      'val' => $val,
      'desc' => $desc,
      'upload' => $upload
    );

    echo $der_framework->render('<div class="metabox-entry{{#upload}} option{{/upload}}" data-upload-label="Upload {{{upload}}}" data-remove-label="Remove {{{upload}}}">
<label for="{{{key}}}">{{{title}}}</label>
{{#upload}}<p>{{/upload}}<input{{#upload}} class="upload-input" style="width: 98%;"{{/upload}} type="text" id="{{{key}}}" name="{{{key}}}" value="{{{val}}}" tabindex="1" autocomplete="off" />{{#upload}}</p>{{/upload}}
{{#desc}}{{#upload}}<p>{{/upload}}<span class="desc">{{{desc}}}</span>{{#upload}}</p>{{/upload}}{{/desc}}
</div>', $vars);

  }
  
  /* Renders a Textarea */
  
  function textarea($args) {
    
    $post_id = get_the_ID();
    
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => '',
      'rows' => 5,
      'cols' => 40
    );
    
    $args = wp_parse_args($args, $defaults);
    extract($args);
    
    $key = $this->prefix . $key;
    
    // Register key & exit on lookup mode
    if ($this->is_lookup_mode()) {
      $this->keys[] = $key; return;
    }
    
    $val = get_post_meta($post_id, $key, true);
    
    if (isset($args['gallery_interface']) && is_array($args['gallery_interface'])) {
      $gi = $args['gallery_interface'];
      $gallery_interface = sprintf('class="gi-load" data-gi-options="%s"', esc_html(json_encode($gi)));
      $val = base64_encode(trim($val));
    } else {
      $gallery_interface = '';
    }
    
    printf("\n" . '<div class="metabox-entry">
<label for="%s">%s</label>
<textarea %s rows="%d" cols="%d" name="%s" id="%s" tabindex="1">%s</textarea>', $key, $title, $gallery_interface, $rows, $cols, $key, $key, $val);
    if ($desc) echo "\n<span class=\"desc\">${desc}</span>\n";
    echo "</div><!-- .metabox-entry -->\n";

  }
  
  /* Renders an icon selection box */
  
  function icon($args) {

    $post_id = get_the_ID();
    
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => null,
      'default' => null
    );
    
    $args = wp_parse_args($args, $defaults);
    extract($args);
    
    $key = $this->prefix . $key;
    
    // Register key & exit on lookup mode
    if ($this->is_lookup_mode()) {
      $this->keys[] = $key; return;
    }
    
    $val = get_post_meta($post_id, $key, true);
    
    printf("\n" . '<div class="metabox-entry ui-icon-chooser">
<label for="%s">%s</label>
<p>
  <span class="preview-icon'. ($val ? ' visible' : '') .'">'. ($val ? sprintf('<i class="%s"></i>', $val) : '<i></i>') .'</span>
  <input type="hidden" name="%s" value="%s" />
</p>', $key, $title, $key, $val);

    if ($desc) echo "\n<span class=\"desc\">${desc}</span>\n";
    echo "</div><!-- .metabox-entry -->\n";
    
    
  }
  
  /* Renders a Select box */
  
  function select($args) {
    
    $post_id = get_the_ID();
    
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => '',
      'width' => null,
      'default' => null,
      'options' => null,
      'numeric' => false
    );
    
    $args = wp_parse_args($args, $defaults);
    extract($args);
    
    $key = $this->prefix . $key;
    
    // Register key & exit on lookup mode
    if ($this->is_lookup_mode()) {
      $this->keys[] = $key; return;
    }
    
    $val = get_post_meta($post_id, $key, true);
    
    if (empty($val) && $default) $val = $default;
    
    if (isset($args['escape']) && $args['escape']) {
      $val = esc_html($val);
    }
    
    $style = ($width) ? sprintf('style="width: %spx;" ', $width) : null;
    
    printf("\n" . '<div class="metabox-entry">
<label for="%s">%s</label>
<select %sname="%s" id="%s">', $key, $title, $style, $key, $key);

    foreach ($options as $k => $v) {
      
      if (is_string($k)) {
        $selected = ( $k == $val ) ? ' selected="selected"' : ' ';
      } else {
        $k = (is_int($k) && $numeric) ? $k : $v;
        $selected = ( ($numeric ? $k : $v) == $val ) ? ' selected="selected"' : ' ';
      }
      
      printf("\n" . '  <option value="%s"%s>%s</option>', $k, $selected, $v);
    }

    echo "\n</select>\n";
    if ($desc) echo "<span class=\"desc\">${desc}</span>\n";
    echo "</div><!-- .metabox-entry -->\n";

  }
  
  /* Renders a layout selection input  */
  
  function layout($args) { global $der_framework;
    $layouts = csv2array($der_framework->layout_keys);
    array_unshift($layouts, '');
    $args['options'] = $layouts;
    $args['escape'] = true;
    $this->select($args);
  }
  
  /* Generates a Radio Metabox */
  
  private function radio_gen($args) {
    
    $post_id = get_the_ID();
    
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => '',
      'options' => null,
      'numeric' => false,
      'default' => true,
      'class' => ''
    );
    
    $args = wp_parse_args($args, $defaults);
    extract($args);
    
    $html = '';
    $key = $this->prefix . $key;
    
    // Register key & exit on lookup mode
    if ($this->is_lookup_mode()) {
      $this->keys[] = $key; return;
    }

    $val = get_post_meta($post_id, $key, true);
    
    $html .= sprintf("\n" . '<div class="metabox-entry mb-radio-check %s">
<label class="nohover">%s</label>', $class, $title);
    
    $default_checked = false;
    $first_k = null;
    
    foreach ($options as $k => $v) {
      
      if (is_string($k)) {
        $checked = ( $k == $val ) ? ' checked="checked"' : ' ';
      } else {
        $k = (is_int($k) && $numeric) ? $k : $v;
        $checked = ( ($numeric ? $k : $v) == $val) ? ' checked="checked"' : ' ';
      }
      
      if ($default) {
        if ($first_k === null) $first_k = $k;
        if (!$default_checked && $checked != ' ') $default_checked = true;
      }
      
      $html .= sprintf("\n" . '<p><label><input type="radio" name="%s" value="%s"%s/><span>%s</span></label></p>', $key, $k, $checked, $v);
    }
    
    if ($default && !$default_checked) {
      $html = preg_replace("/value=\"${first_k}\"/", "value=\"${first_k}\" checked=\"checked\"", $html);
    }
    
    if ($desc) $html .= "\n<span class=\"desc\">${desc}</span>\n";
    $html .= "</div><!-- .metabox-entry -->\n";
    
    return $html;

  }
  
  /* Renders a Radio Metabox */
  
  function radio($args) {
    echo $this->radio_gen($args);
  }
  
  /* Renders a Radio Metabox (inline) */
  
  function radio_inline($args) {
    $args['class'] = 'mb-inline';
    echo $this->radio_gen($args);
  }
  
  /* Generates a Checkbox Metabox */
  
  private function checkbox_gen($args) {
    
    $post_id = get_the_ID();
    
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => '',
      'options' => null,
      'class' => ''
    );
    
    $args = wp_parse_args($args, $defaults);
    $key_format = '%s:%s:_%s';
    extract($args);
    
    // Register keys & exit in lookup mode
    if ($this->is_lookup_mode()) {
      foreach ($options as $k => $v) {
        $k = sprintf($key_format, $this->prefix, $key, $k);
        $this->keys[] = $k;
      }
      return;
    }
    
    $html = '';
    $html .= sprintf("\n" . '<div class="metabox-entry mb-radio-check %s">
<label class="nohover">%s</label>', $class, $title);
  
    foreach ($options as $k => $v) {
      $k = sprintf($key_format, $this->prefix, $key, $k);
      $val = get_post_meta($post_id, $k, true);
      $checked = ($val == 'on') ? 'checked="checked" ' : ' ';
      $html .= sprintf("\n" . '<p><label><input type="checkbox" name="%s"%s/><span>%s</span></label></p>', $k, $checked, $v);
    } 

    if ($desc) $html .= "\n<span class=\"desc\">${desc}</span>\n";
    $html .= "</div><!-- .metabox-entry -->\n";

    return $html;

  }
  
  /* Renders a Checkbox Metabox */
  
  function checkbox($args) {
    echo $this->checkbox_gen($args);
  }
  
  /* Renders a Checkbox Metabox (inline) */
  
  function checkbox_inline($args) {
    $args['class'] = 'mb-inline';
    echo $this->checkbox_gen($args);
  }
  
  /* Taxonomies Metabox Generator */
  
  private function taxonomies_gen($args) {
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => '',
      'taxonomy' => null,
      'args' => '',
      'class' => '',
      'ui_type' => null
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $taxonomies = (array) get_terms($args['taxonomy'], $args['args']);
    $options = array();
    
    foreach ($taxonomies as $term) {
      $options[$term->term_id] = esc_html($term->name);
    }
    
    $common_args = array(
      'key' => $args['key'],
      'title' => $args['title'],
      'desc' => $args['desc'],
      'options' => $options
    );
    
    switch ($args['ui_type']) {
      
      case 'radio':
        $common_args['numeric'] = true;
        echo $this->radio_gen($common_args);
        break;
        
      case 'radio_inline':
        $common_args['numeric'] = true;
        $common_args['class'] = 'mb-inline';
        echo $this->radio_gen($common_args);
        break;
        
      case 'checkbox':
        echo $this->checkbox_gen($common_args);
        break;
        
      case 'checkbox_inline':
        $common_args['class'] = 'mb-inline';
        echo $this->checkbox_gen($common_args);
        break;
        
      case 'select':
        $common_args['numeric'] = true;
        $this->select($common_args);
        break;
        
      default: break;
      
    }
    
  }
  
  /* Renders a Taxonomy Radio */
  
  function taxonomies_radio($args) {
    $args['ui_type'] = 'radio';
    $this->taxonomies_gen($args);
  }
  
  /* Renders a Taxonomy Radio (inline) */
  
  function taxonomies_radio_inline($args) {
    $args['ui_type'] = 'radio_inline';
    $this->taxonomies_gen($args);
  }
  
  /* Renders a Taxonomy Checkbox */
  
  function taxonomies_checkbox($args) {
    $args['ui_type'] = 'checkbox';
    $this->taxonomies_gen($args);
  }
  
  /* Renders a Taxonomies Checkbox Inline */
  
  function taxonomies_checkbox_inline($args) {
    $args['ui_type'] = 'checkbox_inline';
    $this->taxonomies_gen($args);
  }
  
  /* Renders a Taxonomies Select */
  
  function taxonomies_select($args) {
    $args['ui_type'] = 'select';
    $this->taxonomies_gen($args);
  }
  
  /* Post Metabox Generator */
  
  private function post_gen($args) {
    
    $defaults = array(
      'key' => null,
      'title' => null,
      'desc' => '',
      'post_type' => 'post',
      'showposts' => -1,
      'args' => '',
      'ui_type' => null
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // Construct the original query array
    $q = wp_parse_args(sprintf('post_type=%s&showposts=%d', $args['post_type'], $args['showposts']));
    
    // Override query array parameters with the ones provided in $args['args']
    $q = wp_parse_args(wp_parse_args($args['args']), $q);

    // Get posts with previously constructed query
    $query = new WP_Query($q);
    
    $options = array();
    
    foreach ($query->posts as $post) {
      $options[$post->ID] = esc_html($post->post_title);
    }
    
    $common_args = array(
      'key' => $args['key'],
      'title' => $args['title'],
      'desc' => $args['desc'],
      'options' => $options,
    );
    
    switch ($args['ui_type']) {
      
      case 'radio':
        $common_args['numeric'] = true;
        echo $this->radio_gen($common_args);
        break;
        
      case 'select':
        $common_args['numeric'] = true;
        $this->select($common_args);
        break;
        
      case 'checkbox':
        $this->checkbox($common_args);
        break;
        
      default: break;

    }

  }
  
  /* Renders a Post Radio */
  
  function post_radio($args) {
    $args['ui_type'] = 'radio';
    $this->post_gen($args);
  }
  
  /* Renders a Post Select */
  
  function post_select($args) {
    $args['ui_type'] = 'select';
    $this->post_gen($args);
  }
  
  /* Renders a Post Checkbox */
  
  function post_checkbox($args) {
    $args['ui_type'] = 'checkbox';
    $this->post_gen($args);
  }
  
  /* Positional cropping metabox */
  
  function positional_cropping($title) { global $post;
      
    $this->select(array(
      'key' => THUMB_CROP_META,
      'title' => sprintf("%s Cropping", $title),
      'desc' => sprintf("Determines how the %s is cropped/resized.", strtolower($title)),
      'options' => array(
        'c'  => "Center",
        'cl' => "Center Left",
        'cr' => "Center Right",
        't'  => "Top",
        // 'tc' => "Top Center",
        'tl' => "Top Left",
        'tr' => "Top Right",
        'b'  => "Bottom",
        // 'bc' => "Bottom Center",
        'bl' => "Bottom Left",
        'br' => "Bottom Right",
        'bl' => "Bottom Left",
        'l'  => "Left",
        'r'  => "Right"
      )
    ));
    
  }
  
}

?>