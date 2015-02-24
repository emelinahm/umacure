<?php

add_action('widgets_init', 'ShortcodeWidget::register');

class ShortcodeWidget extends WidgetFramework {
  
  function __construct() {
    
    $this->initialize(array(
      'id' => 'quick-shortcode',
      'title' => "Shortcode",
      'description' => "Evaluates a shortcode and inserts its result into the widget.",
      'defaults' => array(
        'title' => "",
        'content' => ""
      )
    ));

  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($args) {
    echo meteor_content($args, '', 'shortcode');
  }
  
  function admin($instance) {
    
    $this->textarea(array(
      'id' => 'content',
      'title' => "Content",
      'description' => "Content to pass to the shortcode (optional, depends on the shortcode).",
      'rows' => 10
    ));
    
  }
  
}

?>