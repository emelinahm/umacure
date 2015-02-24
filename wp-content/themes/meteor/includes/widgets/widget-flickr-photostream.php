<?php

add_action('widgets_init', 'FlickrWidget::register');

class FlickrWidget extends WidgetFramework {
  
  function __construct() {
    
    $this->initialize(array(
      'id' => 'widget_meteor_photostream',
      'title' => "Flickr Gallery",
      'description' => "Photostream of flickr images",
      'defaults' => array(
        'title' => __("Flickr Gallery", "theme"),
        'username' => null,
        'source' => 'user',
        'display' => 'latest',
        'count' => 6
      )
    ));

  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($options) { global $der_framework;
    $username = trim($options['username']);
    if (!empty($username)) {
      $options_attr = theme_options_attr('data-options', array(
        'id' => $username,
        'source' => $options['source'],
        'display' => $options['display'],
        'count' => $options['count']
      ));
      $placeholders = array();
      for ($i=0; $i < $options['count']; $i++) {
        $placeholders[] = array();
      }
      echo $der_framework->render_template('widget-flickr-photostream.mustache', array(
        'options' => $options_attr,
        'items' => $placeholders,
        'in_footer' => $der_framework->in_footer,
        'placeholder' => $der_framework->uri('core/images/empty.png')
      ));
    }
  }
  
  function admin($instance) {
    
    $this->text(array(
      'id' => 'username',
      'title' => "Flickr ID",
      'description' => "Flickr User ID. &nbsp;<a target='_blank' href='http://idgettr.com/'>Don't know your ID?</a>."
    ));
    
    $this->select(array(
      'id' => 'source',
      'title' => "Photos Source",
      'description' => "The source from which the photos will be retrieved.",
      'values' => array(
        'user' => "Flickr User",
        'group' => "Flickr Group",
        'user_set' => "Flickr Set"
      )
    ));
    
    $this->select(array(
      'id' => 'display',
      'title' => "Photos Display",
      'description' => "Which criteria to use when retrieving images.",
      'values' => array(
        'latest' => "Latest Images",
        'random' => "Random Images"
      )
    ));
    
    $this->text(array(
      'id' => 'count',
      'title' => "Number of Images",
      'description' => "Amount of Images to display."
    ));
      
  }
  
}

?>