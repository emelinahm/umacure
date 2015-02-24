<?php

add_action('widgets_init', 'PhpWidget::register');

class PhpWidget extends WidgetFramework {
  
  function __construct() {

    $this->initialize(array(
      'id' => 'php-code',
      'title' => "PHP Code",
      'description' => "Runs custom PHP Code",
      'defaults' => array(
        'title' => "",
        'code' => null
      )
    ));

  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($args) {
    php_code_component($args);
  }
  
  function admin($instance) {
    
    $this->textarea(array(
      'id' => 'code',
      'title' => "PHP Code",
      'description' => "Code to execute.
<br/><br/><strong>DISCLAIMER: ONLY USE THIS IF YOU REALLY KNOW WHAT YOU'RE DOING.</strong>
      "
    ));
    
  }
  
}

?>