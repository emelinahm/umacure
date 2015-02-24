<?php

add_action('widgets_init', 'MeteorTwitterWidget::register');

class MeteorTwitterWidget extends WidgetFramework {
  
  function __construct() {
    
    $this->initialize(array(
      'id' => 'widget_meteor_twitter_feed',
      'title' => "Twitter Feed",
      'description' => "Tweets from your account",
      'defaults' => array(
        'title' => '',
        'username' => null,
        'count' => 3,
        'profile_button' => 'show',
        'button_text' => __("Follow on Twitter", "theme")
      )
    ));

  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($instance) { global $der_framework;
    $username = trim($instance['username']);
    if ($username) {
      $username = preg_replace('/^@/', '', $username);
      $args = array(
        'username' => $username,
        'count' => $instance['count'],
        'profile_button' => ($instance['profile_button'] == 'show'),
        'button_text' => theme_mini_shortcode(esc_html($instance['button_text']))
      );
      echo $der_framework->render_template('widget-twitter-feed.mustache', $args);
    }
  }
  
  function admin($instance) {
    
    $this->text(array(
      'id' => 'username',
      'title' => "Username",
      'description' => "Twitter username to retrieve tweets from.",
    ));
    
    $this->text(array(
      'id' => 'count',
      'title' => "Count",
      'description' => "Amount of tweets to retrieve.",
    ));
    
    $this->select(array(
      'id' => "profile_button",
      'title' => "Profile Button",
      'description' => "Determines if the profile button is displayed.",
      'values' => array(
        'show' => "Show",
        'hide' => "Hide"
      )
    ));
    
    $this->text(array(
      'id' => 'button_text',
      'title' => "Button Text",
      'description' => "Text to use for the profile button."
    ));
      
  }
  
}

?>