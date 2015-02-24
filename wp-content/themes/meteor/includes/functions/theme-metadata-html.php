<?php

  function theme_metadata_html($options, $separate_tags=false) { global $der_framework;

    $html = array();
    $tags_html = array();
    $data = $der_framework->get_post_metadata($options, true);
    
    foreach ($options as $o) {
      switch ($o) {
        case 'date':
          $html[] = sprintf('<li class="meta-date"><i class="icon-calendar"></i> <a href="%s">%s</a></li>', get_permalink(), $data['date']);
          break;
        case 'author':
          $html[] = sprintf('<li class="meta-author"><i class="icon-user"></i> By %s</li>', $data['author']);
          break;
        case 'comments':
          if (isset($data['comments'])) {
            $html[] = sprintf('<li class="meta-comments"><i class="icon-comment"></i> %s</li>', $data['comments']);
          }
          break;
        case 'categories':
          if (isset($data['categories'])) {
            $html[] = sprintf("<li class=\"meta-categories\">\n<i class=\"icon-reorder\"></i>\n%s\n</li>", $data['categories']);
          }
          break;
        case 'tags':
          if (isset($data['tags'])) {
            if ($separate_tags) {
              $tags = sprintf("<li class=\"meta-tags\">\n<i class=\"icon-tags\"></i> <span class=\"label\">". __("Tagged", "theme") .":</span> %s\n</li>", $data['tags']);
              $tags_html = array($tags);
            } else {
              $tags = sprintf("<li class=\"meta-tags\">\n<i class=\"icon-tags\"></i>\n%s\n</li>", $data['tags']);
              $html[] = $tags;
            }
          }
          break;
      }
    }
    
    $template = "<ul class=\"meteor-info clearfix\">\n%s\n</ul><!-- .meteor-info -->";
    $metadata = (empty($html)) ? null : sprintf($template, implode("\n", $html));
    
    if ($separate_tags) {
      
      $combined = array_merge($html, $tags_html);
      
      return array(
        'metadata' => $metadata,
        'metadata_tags' => (empty($tags_html)) ? null : sprintf($template, implode("\n", $tags_html)),
        'metadata_all' => (empty($combined)) ? null : sprintf($template, implode("\n", $combined))
      );
    } else {
      return $metadata;
    }
    
  }

?>