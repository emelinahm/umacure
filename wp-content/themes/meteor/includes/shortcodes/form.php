<?php

  /* Form Shortcode */

  add_filter('form_builder_submit', 'form_builder_submit', 10, 2);
  
  add_action('form_builder_first_load', 'form_builder_first_load');
  
  add_shortcode('form',     array('FormBuilder', 'form'));
  add_shortcode('input',    array('FormBuilder', 'input'));
  add_shortcode('select',   array('FormBuilder', 'select'));
  add_shortcode('radio',    array('FormBuilder', 'radio'));
  add_shortcode('check',    array('FormBuilder', 'check'));
  add_shortcode('checkbox', array('FormBuilder', 'checkbox'));
  add_shortcode('textarea', array('FormBuilder', 'textarea'));
  add_shortcode('submit',   array('FormBuilder', 'submit'));
  
  FormBuilder::init(array(
    'id_base' => 'mf-',
    'class' => 'meteor-form',
    'template' => 'meteor-form.mustache',
    'options' => array(
      'radio' => array('standout'),
      'checkbox' => array('standout')
    )
  ));
      
  function form_builder_first_load() {
    wp_enqueue_script('jquery-form', $in_footer=true);
  }
      
  function form_builder_submit($args, $atts) {
    if (isset($atts['icon'])) $args['icon'] = preg_replace('/^icon-/', '', $atts['icon']);
    if (in_array('medium', $atts)) {
      $args['size'] = 'medium';
    } else if (in_array('small', $atts)) {
      $args['size'] = 'small';
    } else {
      $args['size'] = 'large';
    }
    return $args;
  }
  
?>