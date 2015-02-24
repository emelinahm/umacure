<?php

add_action('widgets_init', 'MeteorBlogActivityWidget::register');

class MeteorBlogActivityWidget extends WidgetFramework {
  
  function __construct() {
    
    $this->initialize(array(
      'id' => 'widget_meteor_blog_activity',
      'title' => "Popular / Recent / Comments",
      'description' => "The most popular and recent posts and comments from your site",
      'defaults' => array(
        'title' => "",
        'visible' => "popular",
        'popular_count' => 3,
        'recent_count' => 3,
        'comment_count' => 3
      )
    ));

  }
  
  static function register() {
    register_widget(__CLASS__);
  }
  
  function render($options) { global $der_framework, $wpdb;
    
    $args = array(
      'says' => __("says", "theme")
    );
    
    $popular = (int) $options['popular_count'];
    $recent = (int) $options['recent_count'];
    $comments = (int) $options['comment_count'];
    
    $args['thumb_size'] = $thumb_size = 57;
    
    $date_format = get_option('date_format');
    $resolution = (int) $der_framework->option('thumb_resolution');
    $lang = explode('%s', __("By %s on %s", "theme"));
    
    if (count($lang) === 3) {
      // String is well formatted
      list($by, $on) = $lang;
      $args['by'] = trim($by);
      $args['on'] = trim($on);
    } else {
      // Fallback if string is badly formatted
      $args['by'] = "By";
      $args['on'] = "on";
    }
    
    // Popular

    if ($popular > 0) {
      
      $args['has_popular'] = true;
      
      $post_format_tax_ids = array();
      $post_formats = get_terms('post_format');
      
      $allow_formats = array('post-format-image', 'post-format-gallery');
      
      foreach ($post_formats as $f) {
        if (!in_array($f->slug, $allow_formats)) {
          $post_format_tax_ids[] = $f->term_id;
        }
      }

      $post_format_tax_ids = implode(',', $post_format_tax_ids);
      $posts_table = $wpdb->prefix . 'posts';
      
      if (empty($post_format_tax_ids)) {
      
        // No post formats to exclude
      
        $query = sprintf("
SELECT id,post_title,post_author,post_date,comment_count
FROM {$wpdb->prefix}posts
WHERE 1=1 
AND post_type = 'post'
AND (post_status = 'publish' OR post_status = 'private')
GROUP BY id
ORDER BY comment_count DESC
LIMIT 0,${popular}");
        
      } else {
        
        // Got post formats to exclude
      
        $query = sprintf("
SELECT id,post_title,post_author,post_date,comment_count
FROM {$wpdb->prefix}posts
WHERE 1=1 
AND ({$wpdb->prefix}posts.id NOT IN (
  SELECT object_id FROM {$wpdb->prefix}term_relationships
  WHERE term_taxonomy_id IN (${post_format_tax_ids})
)) 
AND post_type = 'post'
AND (post_status = 'publish' OR post_status = 'private')
GROUP BY id
ORDER BY comment_count DESC
LIMIT 0,${popular}");
        
      }
      
      $posts = $wpdb->get_results($query);
      
      // pre($posts);
      
      if (count($posts) > 0) {
        $arr = array();
        foreach ($posts as $i => $p) {
          $arr[] = array(
            'id' => $p->id,
            'title' => $p->post_title,
            'permalink' => get_permalink($p->id),
            'author' => get_the_author_meta('display_name', $p->post_author),
            'author_url' => get_the_author_meta('url', $p->post_author),
            'image' => $der_framework->post_thumb($thumb_size*$resolution, $thumb_size*$resolution, $p->id),
            'date' => mysql2date($date_format, $p->post_date)
          );
        }
        $args['popular'] = $arr;
      }
    }
    
    // Recent
    
    if ($recent > 0) {
      
      $args['has_recent'] = true;
      
      $der_framework->query_post_formats = false;
      $posts = query_posts(sprintf("showposts=%s&orderby=date", $recent));
      $der_framework->query_post_formats = THEME_QUERY_POST_FORMATS;
      if (count($posts) > 0) {
        $arr = array();
        foreach ($posts as $p) {
          $arr[] = array(
            'id' => $p->ID,
            'title' => $p->post_title,
            'permalink' => get_permalink($p->ID),
            'author' => get_the_author_meta('display_name', $p->post_author),
            'author_url' => get_the_author_meta('url', $p->post_author),
            'image' => $der_framework->post_thumb($thumb_size*$resolution, $thumb_size*$resolution, $p->ID),
            'date' => mysql2date($date_format, $p->post_date)
          );
        }
        $args['recent'] = $arr;
      }

    }
    
    // Comments
    
    if ($comments > 0) {
      
      $args['has_comments'] = true;
      
      $comments = get_comments(array(
        'type' => 'comment',
        'number' => $comments,
        'status' => 'approve'
      ));

      $trim = 120;
      
      if (count($comments) > 0) {
        $arr = array();
        foreach ($comments as $c) {
          $data = array(
            'id' => $c->comment_ID,
            'avatar' => get_avatar($c->comment_author_email, $thumb_size),
            'comment_content' => (strlen($c->comment_content) > $trim) ? mb_substr($c->comment_content, 0, $trim) . '&hellip;' : $c->comment_content,
            'comment_link' => get_comment_link($c->comment_ID)
          );
          if ((int) $c->user_id === 0) {
            $data['author'] = $c->comment_author;
          } else {
            $data['author'] = get_the_author_meta('display_name', $c->user_id);
            $data['author_url'] = get_the_author_meta('url', $c->user_id);
          }
          $arr[] = $data;
        }
        $args['comments'] = $arr;
      }

    }
    
    // Set default tab
    
    if (isset($options['visible'])) {
      // Set default tab if items were found
      $args[$options['visible'] . '_active'] = true;
    } else {
      // If default tab doesn't have items, fall back to the others
      foreach (array('popular', 'recent', 'comments') as $context) {
        if (isset($args[$context])) {
          $args[$context . '_active'] = true;
          break;
        }
      }
    }
    
    // Set Labels
    
    $args['popular_label'] = __("Popular", "theme");
    $args['recent_label'] = __("Recent", "theme");
    $args['comments_label'] = __("Comments", "theme");
    
    echo $der_framework->render_template('widget-blog-activity.mustache', $args);
    
  }
  
  function admin($instance) {
    
    // $id, $title, $description, $values
    
    $this->select(array(
      'id' => 'visible',
      'title' => "Active Tab",
      'description' => "Determines which content to give priority to.",
      'values' => array(
        'popular' => "Popular Posts",
        'recent' => "Recent Posts",
        'comments' => "Comments"
      )
    ));
    
    $this->text(array(
      'id' => 'popular_count',
      'title' => "Popular Posts",
      'description' => "Amount of popular posts to display.",
    ));
    
    $this->text(array(
      'id' => 'recent_count',
      'title' => "Recent Posts",
      'description' => "Amount of recent posts to display.",
    ));
    
    $this->text(array(
      'id' => 'comment_count',
      'title' => "Comments",
      'description' => "Amount of comments to display.",
    ));
    
  }
  
}

?>