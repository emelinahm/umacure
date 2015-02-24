<?php

class WidgetFramework extends WP_Widget {
  
  var $args;
  var $defaults;
  var $update_taxonomies;
  private $instance;
  
  function initialize($args) {
    
    // $id, $title, $description, $defaults
    $defaults = array(
      'title' => 'hello',
      'classname' => $args['id'],
      'description' => $args['description']
    );
    
    $interface = array(
      'width' => 250, 
      'height' => 350, 
      'id_base' => $args['id']
    );
    
    if (defined('THEME_SIDEBAR_PREFIX')) $args['title'] = THEME_SIDEBAR_PREFIX . $args['title'];
    
    $this->args = $args;
    $this->defaults = $args['defaults'];
    $this->update_taxonomies = isset($args['update_taxonomies']) ? $args['update_taxonomies'] : null;
    $this->WP_Widget($args['id'], $args['title'], $defaults, $interface);
  }
  
  function widget($args, $instance) {
    $title = apply_filters('widget_title', $instance['title']);
    echo $args['before_widget'];
    if ( $title ) { echo $args['before_title'] . $title . $args['after_title']; }
    echo "\n";
    $this->instance = $instance;
    $instance = wp_parse_args($instance, $this->defaults);
    $this->render($instance);
    echo $args['after_widget'];
  }
  
  function onUpdate($instance) {
    return $instance;
  }
  
  function update($new_instance, $old_instance) { global $der_framework;
    $instance = $old_instance;
    $keys = array_keys($this->defaults);

    foreach ($this->update_taxonomies as $tax) {
      $terms = array_keys($der_framework->get_taxonomies($tax));
      foreach ($terms as $t) {
        $keys[] = $tax . '_' . $t;
      }
    }
    
    foreach ($keys as $key) {
      $instance[$key] = stripslashes($new_instance[$key]);
    }
    
    return $this->onUpdate($instance);
  }
  
  function form( $instance ) {
    $this->instance = (array) $instance;
    $defaults = $this->defaults;
    $instance = wp_parse_args((array) $instance, $defaults);
    $this->text(array(
      'id' => 'title',
      'title' => "Widget Title",
      'description' => 'Title to use for the widget.'
    ));
    $this->admin($instance);
  }
  
  function get_checked_taxonomies($taxonomy) {
    $regex = sprintf('/^%s_/', $taxonomy);
    $out = array();
    foreach ($this->instance as $key => $val) {
      if (preg_match($regex, $key) && $val == 'on') {
        $out[] = preg_replace($regex, '', $key);
      }
    }
    return implode(', ', $out);
  }
  
  function text($args) {
    extract($args); // $id, $title, $description
    $value = (array_key_exists($id, $this->instance)) ? $this->instance[$id] : $this->defaults[$id];
    $value = esc_html($value);
    $field_id = $this->get_field_id($id);
    printf('
<div class="option">
<label for="%s">%s</label>
<p><input id="%s" type="text" name="%s" value="%s" /></p>
<small>%s</small>
</div><!-- .option -->', $field_id, $title, $field_id, $this->get_field_name($id), $value, $description);
  }
  
  function textarea($args) {
    extract($args); // $id, $title, $description, $rows
    $value = (array_key_exists($id, $this->instance)) ? $this->instance[$id] : $this->defaults[$id];
    $value = esc_html($value);
    $field_id = $this->get_field_id($id);
    $gallery_interface = (isset($this->args['gallery_interface']) && $this->args['gallery_interface'] == 'content') ? ' class="gallery-interface" ' : '';
    if (empty($rows)) $rows = 5;
    printf('
<div class="option">
<label for="%s">%s</label>
<p><textarea %s name="%s" rows="%d">%s</textarea></p>
<small>%s</small>
</div><!-- .option -->', $field_id, $title, $gallery_interface, $this->get_field_name($id), $rows, $value, $description);
  }

  function select($args) {
    extract($args); // $id, $title, $description, $values
    $field_id = $this->get_field_id($id);
    printf('
<div class="option">
<label for="%s">%s</label>
<select id="%s" name="%s">', $field_id, $title, $field_id, $this->get_field_name($id));

    $default = (array_key_exists($id, $this->instance)) ? $this->instance[$id] : $this->defaults[$id];
  
    foreach ($values as $key => $value) {
      if ($key == $default) {
         printf("\n".'<option selected="selected" value="%s">%s</option>', $key, $value);
      } else {
        printf("\n".'<option value="%s">%s</option>', $key, $value);
      }
    }

    printf('\n</select>
<small>%s</small>
</div><!-- .option -->', $description);
  }

  function taxonomy_select($args) { global $der_framework;
    $args['values'] = array_merge(array(' ' => ' '), $der_framework->get_taxonomies($args['taxonomy']));
    $this->select($args);
  }
  
  function gallery_select($args) {
    $posts = query_posts('post_type=gallery&showposts=-1'); wp_reset_query();
    $values = array();
    foreach ($posts as $p) {
      $values[sprintf('__%d__', $p->ID)] = apply_filters('the_title', $p->post_title);
    }
    $args['values'] = array_merge(array(' ' => ' '), $values);
    $this->select($args);
  }
  
  function checkbox($args) {
    extract($args); // $prefix, $title, $description, $values
    if (empty($id)) $id = null;
    $field_id = $this->get_field_id($id);
    printf('
<div class="option checkbox-option">
<label>%s</label>
<p>', $title);

    foreach ($values as $key => $val) {
      $cb_id = $prefix . '_' . $key;
      if (array_key_exists($cb_id, $this->instance) && $this->instance[$cb_id] == 'on') {
        printf("\n".'<label><input checked="checked" type="checkbox" name="%s" /> %s</label><br/>', $this->get_field_name($cb_id), $val);
      } else {
        printf("\n".'<label><input type="checkbox" name="%s" /> %s</label><br/>', $this->get_field_name($cb_id), $val);
      }
    }

    printf('
</p>
<small>%s</small>    
</div><!-- .option -->', $description);

  }
  
  function taxonomy_checkbox($args) { global $der_framework;
    $args['prefix'] = $args['taxonomy'];
    $args['values'] = $der_framework->get_taxonomies($args['taxonomy']); // Get id's instead of slugs
    $this->checkbox($args);
  }
  
}

?>