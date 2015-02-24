<?php

add_action('widgets_init', 'MeteorPostsScrollerWidget::register');

class MeteorPostsScrollerWidget extends WidgetFramework {

  function __construct() {
    
    $this->initialize(array(
      'id' => 'widget_meteor_posts_scroller',
      'title' => "Meteor Posts Scroller",
      'description' => "Displays your posts using a carousel.",
      'update_taxonomies' => array('category'),
      'defaults' => array(
        'title' => '',
        'columns' => 'auto',
        'category' => null,
        'custom_query' => null,
        'showposts' => 12,
        'thumb_height' => null,
        'thumb_options_permalink' => null,
        'thumb_options_lightbox' => null,
        'thumb_options_gallery' => null,
        'click_behavior' => null,
        'show_description' => null,
        'metadata_display' => 'description'
      )
    ));
        
  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($options) { global $der_framework;
    
    // Set thumb options
    $thumb_options = array();
    foreach (array('permalink', 'lightbox', 'gallery') as $o) {
      if (isset($options['thumb_options_' . $o]) && $options['thumb_options_' . $o] === 'on') {
        $thumb_options[] = $o;
      }
    }
    
    // Set categories
    $category = array();
    $cat_regex = '/^category_/';
    foreach ($options as $k => $v) {
      if (preg_match($cat_regex, $k) && $v === 'on') {
        $category[] = preg_replace($cat_regex, '', $k);
      }
    }
    
    // Set click behavior
    if ($options['click_behavior'] === 'nothing') {
      $options['click_behavior'] = null;
    }
    
    // Show description
    $options['show_description'] = ($options['show_description'] === 'yes');
   
    // Get Columns
    $columns = ($options['columns'] == 'auto')
    ? $der_framework->layout->get_column_distribution()
    : $der_framework->layout->container_columns;
   
    // Get arguments
    $args = array(
      'category' => $category,
      'custom_query' => $options['custom_query'],
      'columns' => $columns,
      'showposts' => $options['showposts'],
      'thumb_height' => $options['thumb_height'],
      'thumb_options' => $thumb_options,
      'click_behavior' => $options['click_behavior'],
      'show_description' => $options['show_description'],
      'metadata_display' => $options['metadata_display'],
      'show_link' => false,
      'title_link' => true,
      'title_align' => 'center',
      'slots' => $der_framework->layout->container_columns
    );
    
    echo meteor_posts_scroller($args);
    
  }
  
  function admin($instance) {
    
    $this->taxonomy_checkbox(array(
      'taxonomy' => 'category',
      'title' => "Categories",
      'description' => 'Categories to retrieve posts from. If left blank, all categories are used.'
    ));
      
    $this->text(array(
      'id' => 'custom_query',
      'title' => "Custom Query",
      'description' => 'Custom Query string to pass. Overrides ALL query options (unless you add & at the beginning).'
    ));
      
    $this->select(array(
      'id' => 'columns',
      'title' => "Column Distribution",
      'description' => "How to split the columns.",
      'values' => array(
        'auto' => "Split Evenly when Possible",
        'container' => "Use Container's Width"
      )
    ));
      
    $this->text(array(
      'id' => 'showposts',
      'title' => "Post Count",
      'description' => 'Amount of posts to retrieve.'
    ));
      
    $this->text(array(
      'id' => 'thumb_height',
      'title' => "Thumbnail Height",
      'description' => 'Height to use for the thumbnails.'
    ));
      
    $this->checkbox(array(
      'prefix' => 'thumb_options',
      'title' => "Thumbnail Options",
      'description' => "",
      'values' => array(
        'permalink' => "Show Permalink button",
        'lightbox' => "Show Lightbox button",
        'gallery' => "Enable Lightbox Gallery"
      )
    ));
        
    $this->select(array(
      'id' => 'click_behavior',
      'title' => "Click Behavior",
      'description' => "Controls how the thumbnail reacts to clicks.",
      'values' => array(
        'nothing' => "Do Nothing",
        'permalink' => "Follow Post Permalink",
        'lightbox' => "Open Lightbox"
      )
    ));
        
    $this->select(array(
      'id' => 'show_description',
      'title' => "Show Description",
      'description' => "Show the description below the title.",
      'values' => array(
        'yes' => "Yes",
        'no' => "No"
      )
    ));
        
    $this->select(array(
      'id' => 'metadata_display',
      'title' => "Show as Description",
      'description' => "Data to display in the description area.",
      'values' => array(
        'description' => "Post Description",
        'author' => "Author",
        'author-date' => "Author / Date",
        'date' => "Published Date",
        'raw-date' => "Date",
        'cats' => "Categories",
        'tags' => "Tags",
        'cats-tags' => "Categories / Tags",
        'author-date-cats' => "Author / Date / Categories",
        'author-date-cats-tags' => "Author / Date / Categories / Tags",
        'author-cats' => "Author / Categories",
        'author-cats-tags' => "Author / Categories / Tags"
      )
    ));
    
  }
  
}

?>
