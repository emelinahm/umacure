<?php

class FormBuilder {

  private static $class;
  private static $id_prefix;
  private static $template;
  private static $uniqid_base;
  
  private static $counter = 0;
  private static $options = array();
  private static $first_load = true;
  private static $active_regex = '/^\*(\s+)?/';

  static function init($config) {
    self::$class = $config['class'];
    self::$id_prefix = $config['id_base'];
    self::$template = $config['template'];
    self::$uniqid_base = substr(md5($config['class']), 0, 10);
    if (isset($config['options'])) self::$options = $config['options'];
  }

  static function set_class($c) {
    self::$class = $c;
  }
  
  static function uniqid() {
    self::$counter++;
    return sprintf('%s%s-%d', self::$id_prefix, self::$uniqid_base, self::$counter);
  }
  
  static function create_id($label, $encode=true, $option=null) {
    $string = preg_replace('/^[ _-]+/', '', trim($label));          // Remove initial underscore chars
    $string = preg_replace('/[ _-]+/', '_', $string);               // Convert separator chars to underscore
    $string = urlencode(strtolower($string));                       // URL Encode + Lowercase
    $string = '__' . $string;                                       // Ensure valid [name] attribute
    if ($encode) $string .= sprintf('[%s]', base64_encode($label));
    if ($option) $string .= sprintf('[%s]', base64_encode($option));
    return $string;
  }
  
  static function set_custom_options($context, $atts, $args) {
    if (isset(self::$options[$context])) {
      foreach (self::$options[$context] as $opt) {
        if (in_array($opt, $atts)) $args[$opt] = true;
      }
    }
    return apply_filters(sprintf('form_builder_%s', $context), $args, $atts);
  }

  static function form($atts, $content='', $code='') { global $der_framework;
    
    if (self::$first_load) {
      do_action('form_builder_first_load');
      self::$first_load = false;
    }
    
    $atts = (array) $atts;
    
    $defaults = array(
      'recipient' => '',
      'cc' => null,
      'bcc' => null,
      'from_name' => null,
      'from_email' => null,
      'subject' => null
    );

    $args = $metadata = wp_parse_args($atts, $defaults);
    $args = self::set_custom_options('form', $atts, $args);
    
    // Antispam
    $antispam = $args['antispam'] = in_array('antispam', $atts);
    
    // Content
    $content = $der_framework->shortcode(trim($content));
    $content = preg_replace('/<br \\/>/', "", $content);
    $content = str_replace('###br###', '<br />', $content);
    $args['content'] = trim($content);
    
    // Metadata
    preg_match_all('/name="([^\"]+)"/', $content, $matches);
    $keys = array_unique($matches[1]);
    $keys = array_values($keys); // Get keys in real order
    $metadata['keys'] = $keys;
    $args['metadata'] = $der_framework->encrypt_array($metadata);
    
    // Other variables
    $args['class'] = self::$class;
    $args['action'] = admin_url('admin-ajax.php?action=sendmsg_submit');
    
    // Obscure action
    if ($antispam) $args['action'] = base64_encode($args['action']);
    
    return $der_framework->render('
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
  
<form class="{{{class}}}" method="post"{{#antispam}} action="#"{{/antispam}}{{^antispam}} action="{{{action}}}"{{/antispam}}>

{{{content}}}

<input type="hidden" name="__metadata__" value="{{{metadata}}}" />{{#antispam}}
<input type="hidden" name="__action__" value="{{{action}}}" />{{/antispam}}

</form><!-- .{{{class}}} -->

<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++ -->'."\n", $args);
    
  }
  
  static function input($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null,
      'validates' => null,
      'value' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('input', $atts, $args);
    
    if (in_array('required', $atts)) $args['required'] = true;
    if (in_array('placeholder', $atts)) $args['placeholder'] = true;
    if (in_array('nolabel', $atts)) $args['nolabel'] = true;
    
    if (in_array('dual', $atts)) {
      $args['is_dual'] = true;
      $args['dual_type'] = 'text';
    }

    if (in_array('first', $atts)) $args['first'] = true;
    if (in_array('last', $atts)) $args['last'] = true;

    $args['name'] = self::create_id($args['label']);
    $args['id'] = self::uniqid();
    $args['is_input'] = true;

    return $der_framework->render_template(self::$template, $args);
    
  }
  
  static function select($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('select', $atts, $args);
    
    if (in_array('required', $atts)) $args['required'] = true;
    
    if (in_array('dual', $atts)) {
      $args['is_dual'] = true;
      $args['dual_type'] = 'select';
    }

    if (in_array('first', $atts)) $args['first'] = true;
    if (in_array('last', $atts)) $args['last'] = true;
    
    $args['name'] = self::create_id($args['label']);
    $args['id'] = self::uniqid();
    $args['is_select'] = true;
    
    $selected = false;
    $options = csv2array(trim($content));
    
    $args['options'] = array();
    
    foreach ($options as $opt) {
      
      // Trim extra characters added by wordpress
      // http://www.php.net/manual/en/function.trim.php#90413
      $opt = trim($opt, "\xc2\xa0");
      $arr = array('value' => $opt);
      if (!$selected && preg_match(self::$active_regex, $opt)) {
        $arr['value'] = preg_replace(self::$active_regex, '', $opt);
        $arr['selected'] = $selected = true;
      }
      $args['options'][] = $arr;
    }
    
    return $der_framework->render_template(self::$template, $args);
    
  }
  
  static function radio($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('radio', $atts, $args);
    
    $args['name'] = self::create_id($args['label']);
    $args['is_radio'] = true;
    
    $checked = false;
    $options = csv2array(trim($content));
    
    $args['options'] = array();
    
    foreach ($options as $opt) {
      $arr = array('value' => $opt);
      if (!$checked && preg_match(self::$active_regex, $opt)) {
        $arr['value'] = preg_replace(self::$active_regex, '', $opt);
        $arr['checked'] = $checked = true;
      }
      $args['options'][] = $arr;
    }
    
    return $der_framework->render_template(self::$template, $args);
    
  }
  
  static function check($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null,
      'enables' => ""
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('check', $atts, $args);
    
    if (in_array('nolabel', $atts)) $args['nolabel'] = true;
    if (in_array('checked', $atts)) $args['checked'] = true;
    
    $args['is_check'] = true;
    $args['name'] = self::create_id($args['label']);
    $args['id'] = self::uniqid();
    
    if (!empty($args['enables'])) {
      $enables = csv2array($args['enables']);
      foreach ($enables as $i => $field) {
        $enables[$i] = self::create_id($field);
      }
      $args['enables'] = implode($enables, ',');
    }
    
    return $der_framework->render_template(self::$template, $args);
    
  }
  
  static function checkbox($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('checkbox', $atts, $args);
    
    $options = csv2array(trim($content));

    $args['is_checkbox'] = true;
    $args['options'] = array();
    
    foreach ($options as $opt) {
      $arr = array(
        'name' => self::create_id($args['label'], true, $opt),
        'label' => $opt,
        'id' => self::uniqid()
      );
      if (preg_match(self::$active_regex, $opt)) {
        $arr['label'] = preg_replace(self::$active_regex, '', $opt);
        $arr['name'] = self::create_id($args['label'], true, $arr['label']);
        $arr['checked'] = true;
      }
      $args['options'][] = $arr;
    }
    
    return $der_framework->render_template(self::$template, $args);
    
  }
  
  static function textarea($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null,
      'rows' => 5,
      'cols' => 10,
      'validates' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('textarea', $atts, $args);

    if (in_array('required', $atts)) $args['required'] = true;
    
    if (in_array('dual', $atts)) {
      $args['is_dual'] = true;
      $args['dual_type'] = 'select';
    }
    
    if (in_array('first', $atts)) $args['first'] = true;
    if (in_array('last', $atts)) $args['last'] = true;
    
    $args['name'] = self::create_id($args['label']);
    $args['id'] = self::uniqid();
    $args['is_textarea'] = true;
    
    return $der_framework->render_template(self::$template, $args);
    
  }
  
  static function submit($atts, $content='', $code='') { global $der_framework;
    
    $atts = (array) $atts;
    
    $defaults = array(
      'label' => null
    );
    
    $args = shortcode_atts($defaults, $atts);
    $args = self::set_custom_options('submit', $atts, $args);
    
    $args['is_submit'] = true;
    $args['id'] = self::uniqid();
    
    return $der_framework->render_template(self::$template, $args);
    
  }
  
}
  
?>