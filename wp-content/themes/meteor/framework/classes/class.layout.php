<?php

/* Layout */

define('LAYOUT_GRID_SIZE', 12);
define('GOLDEN_RATIO_FACTOR', 0.618033988);

class Layout {
  
  var $classes;
  var $callbacks;
  var $column_widths;
  var $container_columns;
  var $column_distributions;
  var $open_container = "\n<section class=\"block container\">\n<div class=\"row\">\n";
  var $close_container = "\n</div><!-- .row -->\n</section><!-- .block -->\n";
  
  function __construct() { global $der_framework, $layout_callbacks;
    
    // Set layout callbacks
    require(TEMPLATEPATH . '/includes/layout/callbacks.php');
    
    $this->callbacks = $layout_callbacks;

    // Set columns widths
    $this->column_widths = array(
      
      "960" => array(
        1 => 60,
        2 => 140,
        3 => 220,
        4 => 300,
        5 => 380,
        6 => 460,
        7 => 540,
        8 => 620,
        9 => 700,
        10 => 780,
        11 => 860,
        12 => 940
      ),
      
      "1170" => array(
        1 => 70,
        2 => 170,
        3 => 270,
        4 => 370,
        5 => 470,
        6 => 570,
        7 => 670,
        8 => 770,
        9 => 870,
        10 => 970,
        11 => 1070,
        12 => 1170
      )

    );
    
    $this->column_distributions = array(
      1 => 1,
      2 => 2,
      3 => 3,
      4 => 4,
      5 => 5,
      6 => 3,
      7 => 7,
      8 => 4,
      9 => 3,
      10 => 5,
      11 => 11,
      12 => 3
    );
    
    // Set classes
    $this->classes = array(
      1 => 'span1',
      2 => 'span2',
      3 => 'span3',
      4 => 'span4',
      5 => 'span5',
      6 => 'span6',
      7 => 'span7',
      8 => 'span8',
      9 => 'span9',
      10 => 'span10',
      11 => 'span11',
      12 => 'span12'
    );
    
  }
  
  /* Retrieves a column width */
  
  function get_column_width($slots) { global $der_framework;
    return $this->column_widths[$der_framework->option('content_width')][$slots];
  }
  
  /* Gets a column distribution based on the parent container */
  
  function get_column_distribution() {
    return $this->column_distributions[$this->container_columns];
  }
  
  /* Renders a nested container */
  
  function render_nested_container($container, $parent_columns) { global $der_framework, $content_width;

    $slot = $container[0];

    $this->container_columns = $slot['s'];
    
    if ($slot['s'] == LAYOUT_GRID_SIZE && is_array($slot['c']) && $this->callbacks[$slot['c']['s']][1] == LAYOUT_GRID_SIZE) {

      return;
      
    } else {

      // Normal component(s)
      
      foreach ($container as $slot) {
        
        if (is_array($slot['c'])) {
          
          //////////////// CONTENT WIDTH
          $content_width = $this->column_widths[$der_framework->option('content_width')][$slot['s']];
          
          // Component available
          $comp = $slot['c'];
          $options = $comp['o'];
          $options['slots'] = $parent_columns;
          $options['container_class'] = $this->column_class($parent_columns);
          
          $callback = $this->callbacks[$comp['s']][0];
          echo $callback($options, (isset($options['content']) ? $options['content'] : null), $comp['s']) . "\n\n";
          
        } else {
          
          // Empty slot
          
          $this->empty_slot($slot);
          
        }
      }

    }

  }
  
  /* Returns the width of the current container */
  
  function get_container_width() {
    return $this->get_column_width($this->container_columns);
  }
  
  /* Renders a container */
  
  function render_container($container) { global $der_framework, $content_width;

    $slot = $container[0];
    
    $this->container_columns = $slot['s'];

    if ($slot['s'] == LAYOUT_GRID_SIZE && is_array($slot['c']) && $this->callbacks[$slot['c']['s']][1] == LAYOUT_GRID_SIZE) {

      // Full width component
      
      //////////////// CONTENT WIDTH
      $content_width = $this->column_widths[$der_framework->option('content_width')][LAYOUT_GRID_SIZE];
  
      $comp = $slot['c'];
      $options = $comp['o'];
      $options['slots'] = $slot['s'];
      $options['container_class'] = $this->column_class($slot['s']);
      
      $callback = $this->callbacks[$comp['s']][0];
      echo $callback($options, (isset($options['content']) ? $options['content'] : null), $comp['s']);
    
    } else {

      // Normal component(s)
      
      echo $this->open_container;

      foreach ($container as $slot) {
        
        if (is_array($slot['c'])) {
          
          //////////////// CONTENT WIDTH
          $content_width = $this->column_widths[$der_framework->option('content_width')][$slot['s']];
          
          // Component available
          $comp = $slot['c'];
          $options = $comp['o'];
          $options['slots'] = $slot['s'];
          $options['container_class'] = $this->column_class($slot['s']);
          
          $callback = $this->callbacks[$comp['s']][0];
          echo $callback($options, (isset($options['content']) ? $options['content'] : null), $comp['s']);
          
        } else {
          
          // Empty slot
          
          $this->empty_slot($slot);
          
        }
      }

      echo $this->close_container;

    }

  }
    
  /* Gets the column class */
  
  function column_class($cols) { global $der_framework;
    return $this->classes[$cols];
  }
  
  /* Renders an empty slot */
  
  function empty_slot($slot) {
    printf("\n\n".'<div class="%s"></div>'."\n", $this->column_class($slot['s']));
  }
  
  /* Generates code for a shortcode call */
  
  function gen_shortcode($shortcode, $options) {
    $code = '';
    
    // Opening
    $code = sprintf('[%s', $shortcode);
    
    foreach ($options as $key => $val) {
      if ($key == 'content') continue;
      $val = preg_replace('/"/', '\\"', $val); // Escape double quotes
      $code .= sprintf(' %s="%s"', $key, $val);
    }
    
    if (array_key_exists('content', $options)) {
      // Long form
      $code .= sprintf(']%s[/%s]', $options['content'], $shortcode);
    } else {
      // Short form
      $code .= ' /]';
    }
    
    return $code;
  }
  
  /* Parses options */
  
  function parse_options($options) {
    $out = array();
    foreach ($options as $option) {
      switch ($option->type) {
        case 'text':
        case 'textarea':
          $out[$option->id] = ($option->value) ? $option->value : null;
          break;
        case 'select':
        case 'radio':
          foreach ($option->values as $o) {
            if ( (property_exists($o, 'selected') && $o->selected) || (property_exists($o, 'checked') && $o->checked) ) {
              $out[$option->id] = $o->value;
              break;
            }
          }
          break;
        case 'checkbox':
          $checked = array();
          foreach ($option->values as $o) {
            if ( property_exists($o, 'checked') && $o->checked ) {
              $checked[] = $o->value;
            }
          }
          $out[$option->id] = implode(', ', $checked);
          break;
        default:
          break;
      }
    }
   return $out; 
  }
  
}

?>