<?php

  function meteor_single_post($atts, $content='', $code='') { global $der_framework;

    $defaults = array(
      'thumb_height' => null,
      'thumb_aspect_ratio' => 'original',
      'thumb_options' => array(),
      'click_behavior' => null,
      'show_post_tags' => true,
      'show_nextprev_nav' => true,
      'nextprev_title' => false,
      'show_featured_image' => true,
      'show_share_links' => true,
      'show_author_bio' => true,
      'show_comments' => true
    );
    
    $args = wp_parse_args($atts, $defaults);
    
    $args['single_post'] = true;
    $args['post_style_normal'] = true;
    
    $args['width'] = $der_framework->layout->get_column_width($args['slots']);
    
    // Set thumb height based on aspect ratio
    theme_set_thumb_aspect_ratio($args);
    
    // Set thumb options
    theme_set_thumb_options($args);
    
    $args = component_get_posts($args, array(
      'featured_image' => $args['show_featured_image'],
      'default_query' => true,
      'chunks' => true,
      'reset_query' => true,
      'title' => true,
      'excerpt' => false,
      'content' => false,
      'shortcodes' => false,
      'show_description' => false,
      'permalink' => true,
      'edit_post_link' => true,
      'post_class' => true,
      'post_formats' => true,
      'pagination' => false
    ));
    
    $post = $args['posts'][0];

    $p = get_post($post['id']);
    
    setup_postdata($p);
    
    $is_attachment = is_attachment();
    
    $rand = $args['rand'] = $der_framework->rand_str();

    $html = $der_framework->render_template('meteor-blog-posts.mustache', $args);
      
    list($open, $close) = explode($rand, $html);
    
    
    // OPENING

    echo $open;
    
    

    // POST CONTENT

    $no_content_formats = array('chat', 'aside', 'quote', 'status');
    
    if (!in_array($post['post_format'], $no_content_formats)) {
    
    echo '
<div class="post-content">
  <div class="inner-content clearfix">'."\n\n";
  
    if (is_attachment()) {
      
      $url = wp_get_attachment_url();

      // pre($p);
      
      if (wp_attachment_is_image()) {

        $metadata = wp_get_attachment_metadata();
        
        $container_width = $der_framework->layout->get_column_width($args['slots']);
        
        $resize = ($metadata['width'] > $container_width);
        
        echo $der_framework->render('
<div class="attachment-info text-center">
<a class="lite-rounded" href="{{{url}}}"><img alt="{{title}}" width="{{{width}}}"{{#height}} height="{{{height}}}"{{/height}} src="{{{image}}}" /></a>
{{#description}}<div class="desc text-center">{{{description}}}</div>{{/description}}
</div><!-- .attachment-info -->', array(
  
          'url' => $url,
          'title' => get_the_title(),
          'resize' => $resize,
          'width' => ($resize) ? $container_width : $metadata['width'],
          'height' => ($resize) ? null : $metadata['height'],
          'image' => ($resize) ? $der_framework->thumb_src($url, $container_width, 0) : $url,
          'description' => $der_framework->content($p->post_excerpt, false)

        ));
        
      } else if (preg_match('/audio\//', $p->post_mime_type)) {
        
        // Audio Attachment
        
        echo $der_framework->render('
<div class="attachment-info">
  <div class="post-audio standout">
    <audio src="{{{url}}}" preload="auto" controls="controls" ></audio>
  </div><!-- .post-audio -->
  {{#description}}<div class="desc text-center">{{{description}}}</div>{{/description}}
</div><!-- .attachment-info -->', array(
          
          'url' => $url,
          'description' => $der_framework->content($p->post_excerpt, false)
          
        ));
        
      } else if (preg_match('/video\//', $p->post_mime_type)) {
        
        // Video Attachment
        
        echo $der_framework->render('
<div class="attachment-info">
  <div class="post-video">
    <video id="wp-attachment-video-{{{id}}}" class="video-js vjs-default-skin" controls="controls" width="100%">
      <source src="{{{url}}}" type="{{{video_mimetype}}}" />
    </video>
  </div><!-- .post-video -->
  {{#description}}<div class="desc text-center">{{{description}}}</div>{{/description}}
</div><!-- .attachment-info -->', array(
          
          'id' => $p->ID,
          'url' => $url,
          'video_mimetype' => $p->post_mime_type,
          'description' => $der_framework->content($p->post_excerpt, false)
  
        ));
        
      } else {
        
        // Other attachment
        
        echo $der_framework->render('
<div class="attachment-info">
  <p class="standout"><a class="link-hover-accent notd" href="{{{url}}}"><i class="icon-save"></i> &nbsp;&nbsp;{{title}}</a></p>
  {{#description}}<div class="desc">{{{description}}}</div>{{/description}}
</div><!-- .attachment-info -->', array(
            
            'url' => $url,
            'description' => $der_framework->content($p->post_excerpt, false),
            'title' => get_the_title(),
            'filename' => basename($url)
            
          ));
        
      }
      
      
      // Attachment Content
      
      echo $der_framework->render('{{#content}}
<div class="attachment-content">
  {{{content}}}
</div><!-- .attachment-content -->
{{/content}}', array(
  
       'content' => $der_framework->content($p->post_content, false)
  
      ));

      
    } else {
      
      the_content();
      
      wp_link_pages(array(
        'before' => '<p class="wp-link-pages standout"><strong>' . __("Pages", "theme") . ':</strong> &nbsp;',
        'after' => '</p>',
        'pagelink' => '<span>%</span>'
      ));
      
    }
  
    echo $der_framework->render("\n\n".'
  </div><!-- .inner-content -->
  {{#edit_post_link}}{{{edit_post_link}}}{{/edit_post_link}}
</div><!-- .post-content -->

<!-- + -->', $post);

    }
  
  
    // POST TAGS
    
    if (!$is_attachment && $args['show_post_tags']) {
      
      $terms = get_the_terms($p->ID, 'post_tag');
    
      if (!empty($terms)) {
      
        $tags = array();
        foreach ($terms as $term) {
          $tags[] = array(
            'name' =>$term->name,
            'url' => get_term_link($term, $term->taxonomy)
          );
        }
        $post['has_tags'] = true;
        $post['tags'] = $tags;
        $post['tags_label'] = __("Tags", "theme");
      
        echo $der_framework->render('{{#has_tags}}
      <!-- + -->
        
      <div class="single-tags-list clearfix">
        <strong>{{{tags_label}}}:</strong>
      {{#tags}}
        <a class="meteor-capsule" href="{{{url}}}">{{name}}</a>
      {{/tags}}
      </div><!-- .tags-list -->{{/has_tags}}', $post);

      }
      
    }
    
    // NEXT AND PREV LINKS
    
    if (!$is_attachment && $args['show_nextprev_nav']) {
      
        $adjacent_links = $der_framework->adjacent_post_links();
        
        echo $der_framework->render('{{#shownav}}
        <!-- + -->

        <div class="single-post-navigation">
          {{#prev}}<div class="nav-container prev clearfix">
            <a class="post-link" href="{{{prev}}}">
              <i class="icon-angle-left"></i>
              <span class="visible-phone">{{prev_label}}</span>
              <span class="hidden-phone">{{#prev_title}}{{{prev_title}}}{{/prev_title}}{{^prev_title}}{{prev_label}}{{/prev_title}}</span>
        {{!        <!-- <span class="hidden-phone">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</span> --> }}
            </a>
          </div><!-- .nav-container -->{{/prev}}
          {{#next}}<div class="nav-container next clearfix">
            <a class="post-link" href="{{{next}}}">
              <span class="visible-phone">{{next_label}} <i class="icon-angle-right"></i></span>
              <span class="hidden-phone">{{#next_title}}{{{next_title}}}{{/next_title}}{{^next_title}}{{next_label}}{{/next_title}} <i class="icon-angle-right"></i></span>
        {{!        <!-- <span class="hidden-phone">Ut enim ad minim et ven  veniam, quis nostrud exercitation ullamco <i class="icon-angle-right"></i></span> --> }}
            </a>
          </div><!-- .nav-container -->{{/next}}
        </div><!-- .single-post-navigation -->{{/shownav}}', array(
    
        'prev' => $adjacent_links['prev'],
        'next' => $adjacent_links['next'],
        'prev_title' => $args['nextprev_title'] ? $adjacent_links['prev_title'] : null,
        'prev_label' => __("Previous Entry", "theme"),
        'next_title' => $args['nextprev_title'] ? $adjacent_links['next_title'] : null,
        'next_label' => __("Next Entry", "theme"),
        'shownav' => $adjacent_links['prev'] || $adjacent_links['next']

      ));
      
    }
    
    

    // SHARE POST BUTTONS
    
    if ($args['show_share_links']) {
      
      $image_post_formats = array('standard', 'gallery', 'image');
      
      echo $der_framework->render('

      <!-- + -->

      <div class="share-links">
        <strong>'. ($is_attachment ? __("Share attachment", "theme") : __("Share this story", "theme")) .'</strong>
        <ul class="social-icons tooltips clearfix" data-tooltip-options="placement: top, delay.show: 200, delay.hide: 80, container: body">
          {{#image}}<li data-share="pinterest" title="Pinterest"><a class="social-{{{icon_color}}}-24 pinterest" href="http://pinterest.com/pin/create/button/?url={{{url}}}&media={{{image}}}&description={{{title}}}"></a></li>{{/image}}
          <li data-share="twitter" title="Twitter"><a class="social-{{{icon_color}}}-24 twitter" href="http://twitter.com/home?status={{{title}}}{{{space}}}{{{url}}}"></a></li>
          <li data-share="facebook" title="Facebook"><a class="social-{{{icon_color}}}-24 facebook" href="http://www.facebook.com/sharer.php?u={{{url}}}"></a></li>
          <li data-share="reddit" title="Reddit"><a class="social-{{{icon_color}}}-24 reddit" href="http://reddit.com/submit?url={{{url}}}"></a></li>
          <li data-share="linkedin" title="Linkedin"><a class="social-{{{icon_color}}}-24 linkedin" href="http://linkedin.com/shareArticle?mini=true&url={{{url}}}"></a></li>
          <li data-share="digg" title="Digg"><a class="social-{{{icon_color}}}-24 digg" href="http://digg.com/submit?phase=2&url={{{url}}}&bodytext=&tags=&title={{{title}}}"></a></li>
          <li data-share="delicious" title="Delicious"><a class="social-{{{icon_color}}}-24 delicious" href="http://www.delicious.com/post?v=2&url={{{url}}}&notes=&tags=&title={{{title}}}"></a></li>
          <li data-share="googleplus" title="Google Plus"><a class="social-{{{icon_color}}}-24 googleplus" href="http://google.com/bookmarks/mark?op=edit&bkmk={{{url}}}&title={{{title}}}"></a></li>
          <li data-share="email" title="'. __("Email", "theme") .'"><a class="social-{{{icon_color}}}-24 email" href="mailto:?subject={{{title}}}&body={{{url}}}"></a></li>
        </ul><!-- .social-icons -->
      </div><!-- .share-links -->', array(
        
        'url' => urlencode($post['permalink']),
        'title' => urlencode($post['title']),
        'image' => (in_array($post['post_format'], $image_post_formats)) ? $post['image_src'] : null,
        'space' => urlencode(" "),
        'icon_color' => $der_framework->color_theme_option('share_icons_color', 'black')
      ));
      
    }
    



    // AUTHOR BIO
    
    if (!$is_attachment && $args['show_author_bio']) {
      
      echo $der_framework->render('{{#description}}      
      <!-- + -->
    
      <div class="author-bio clearfix">
        <a class="avatar rounded" href="{{{author_url}}}">{{{avatar}}}</a>
        <aside class="post-content">
          <h3 class="author"><a href="{{{author_url}}}">{{display_name}}</a></h3>
          {{{description}}}
        </aside>
      </div><!-- .author-bio -->{{/description}}', array(
      
        'avatar' => get_avatar(get_the_author_meta('ID'), 80),
        'author_url' => get_the_author_meta('user_url'),
        'display_name' => get_the_author_meta('display_name'),
        'description' => $der_framework->content(get_the_author_meta('description'), false)
      
      ));
      
    }
    

    
    // COMMENTS

    if ($args['show_comments'] && comments_open()) comments_template();

    // CLOSING
    
    // pre_html($close);
    
    echo $close;
    
    return null;
    
  }

?>