<?php

add_action('widgets_init', 'GoogleMapShortcode::register');

class GoogleMapShortcode extends WidgetFramework {
  
  function __construct() {
    
    $this->initialize(array(
      'id' => 'widget_meteor_googlemaps',
      'title' => "Google Maps",
      'description' => "An embeddable Google Map",
      'defaults' => array(
        'title' => __("Google Map", "theme"),
        'latlong' => null,
        'type' => 'ROADMAP',
        'zoom' => 16,
        'height' => 400,
        'map_title' => '',
        'link' => '',
        'content' => '',
        'tooltip' => ''
      )
    ));

  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($args) {
    $args['title'] = $args['map_title'];
    $args['fullscreen'] = false;
    unset($args['map_title']);
    echo meteor_google_map($args);
  }
  
  function admin($instance) {

    $this->text(array(
      'id' => 'latlong',
      'title' => "Latitude / Longitude",
      'description' => "Latitude/Longitude string for the location coordinates. <br/><strong>Note:</strong> to get this value, right click the location on a <a target='_blank' href='http://maps.google.com'>google map</a>, and click on \"What's here?\". Click on the green marker that will appear and you will see the coordinates. <br/><strong>Example:</strong> <code>48.858391,2.294083</code>",
    ));
    
    $this->select(array(
      'id' => 'type',
      'title' => "Map Type",
      'description' => "Choose the type of map to render.",
      'values' => array(
        'HYBRID' => "Hybrid",
        'ROADMAP' => "Street Map",
        'SATELLITE' => "Satellite",
        'TERRAIN' => "Terrain"
      )
    ));
    
    $this->text(array(
      'id' => 'zoom',
      'title' => "Map Zoom",
      'description' => "Zoom to use on the map.",
    ));
    
    $this->text(array(
      'id' => 'height',
      'title' => "Map Height (pixels)",
      'description' => "Height to use for the map.",
    ));
    
    $this->text(array(
      'id' => 'map_title',
      'title' => "InfoBox Title",
      'description' => "Title to use for the map infobox balloon. <br/><strong>Note: </strong> this value must be set in order for the <u>infobox and marker</u> to appear."
    ));
    
    $this->text(array(
      'id' => 'link',
      'title' => "InfoBox Title Link",
      'description' => "Link to add to the infobox title."
    ));
    
    $this->textarea(array(
      'id' => 'content',
      'title' => "InfoBox Content",
      'description' => "Content to add to the infobox. <br/><strong>Note: </strong> this value must be set in order for the <u>infobox and marker</u> to appear.",
      'rows' => 6
    ));
    
    $this->text(array(
      'id' => 'tooltip',
      'title' => "Marker Tooltip",
      'description' => "This is the text that will appear when having the mouse over the map marker."
    ));

  }
  
}

?>