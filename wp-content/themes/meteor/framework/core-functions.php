<?php

  /* Core Functions */
  
  /* Loads the theme-header.php file on the 'get_header' action. */
  
  add_action('wp_head', 'theme_custom_css');
  add_action('wp_footer', 'theme_custom_javascript');
  add_action('pre_get_posts', 'theme_get_posts');
  add_action('get_header', 'theme_load_header_file');
  
  add_filter('the_content', 'remove_empty_paragraphs', 9999);
  add_filter('get_the_generator_xhtml', 'theme_generator');
  
  function remove_empty_paragraphs($content) {
    $content = str_replace('<p></p>', '', $content);
    $content = preg_replace('/^(\s+)?<\/p>/', '', $content);
    $content = preg_replace("/<p>\n+?\s+?<\/([^p].*)>/", '</$1>', $content);
    return $content;
  }
  
  function theme_get_posts($query) { global $der_framework;
    
    if (!$der_framework->query_post_formats) {
      $manual_exclude = $der_framework->exclude_post_formats;
      $der_framework->query_post_formats = THEME_QUERY_POST_FORMATS;
      $der_framework->exclude_post_formats = null;
      $tax_query = $query->get('tax_query');
      $tax_query = is_array($tax_query) ? $tax_query : array();
      $tax_query[] = array(
        'taxonomy' => 'post_format',
        'field' => 'slug',
        'terms' => is_array($manual_exclude) ? $manual_exclude : array(
          'post-format-aside', 
          // 'post-format-gallery',
          'post-format-link', 
          // 'post-format-image',
          'post-format-quote',
          'post-format-status', 
          'post-format-video', 
          'post-format-audio', 
          'post-format-chat'),
        'operator' => 'NOT IN'
      );
      $query->set('tax_query', $tax_query);
    }
    
  }
  
  function theme_load_header_file() {
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1) ) { 
      wp_enqueue_script('comment-reply', $in_footer=false);
    }
    include(TEMPLATEPATH . '/includes/theme-header.php');
  }
  
if (!function_exists('theme_breadcrumbs')) {
  
  function theme_breadcrumbs() { global $der_framework;
    
    // Uses code from http://dimox.net/wordpress-breadcrumbs-without-a-plugin/

    $basename = $der_framework->option('breadcrumb_base_name');

    $showOnHome = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter = ' <span class="sep"></span> '; // delimiter between crumbs
    $home = ($basename) ? $basename : get_bloginfo('name'); // text for the 'Home' link
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb

    $html = array();

    global $post;
    $homeLink = home_url();

    if (is_home() || is_front_page()) {

      if ($showOnHome == 1) $html[] = '<a href="' . $homeLink . '">' . $home . '</a>';

    } else {

      $html[] = '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

      if ( is_category() || is_theme_category() ) { global $term, $taxonomy;
        
        if (is_theme_category()) {
          $cat = get_term_by('slug', $term, $taxonomy);
        } else {
          $cat = get_category(get_query_var('cat'));
        }
        
        if ($cat->parent != 0) {
          $parents = get_term_parents($cat->term_id, $cat->taxonomy, true, ' ' . $delimiter . ' ');
          $parents = explode($delimiter, $parents);
          array_pop($parents);
          array_pop($parents);
          $html[] = implode(' ' . $delimiter . ' ', $parents) . ' ' . $delimiter;
        }
        $html[] = $before . sprintf('%s "', __("Archive by category", "theme")) . esc_html($cat->name) . '"' . $after;

      } elseif ( is_search() ) {
        $html[] = $before . sprintf('%s "', __("Search results for", "theme")) . get_search_query() . '"' . $after;

      } elseif ( is_day() ) {
        $html[] = '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        $html[] = '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
        $html[] = $before . get_the_time('d') . $after;

      } elseif ( is_month() ) {
        $html[] = '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        $html[] = $before . get_the_time('F') . $after;

      } elseif ( is_year() ) {
        $html[] = $before . get_the_time('Y') . $after;

      } elseif ( is_single() && !is_attachment() ) {
        
        $post_type = get_post_type();
        
        if ($post_type == 'post' || $post_type == 'portfolio') {
          
          if ($post_type == 'post') {
            $cats = get_the_category(); 
          } else {
            $cats = wp_get_post_terms(get_the_ID(), $post_type . '-category');
          }

          $max = 0;
          $parents = '';
          
          foreach ($cats as $cat) {
            $chain = get_term_parents($cat->term_id, $cat->taxonomy, true, ' ' . $delimiter . ' ');
            $count = substr_count($chain, '<a href=');
            if ($count >= $max) {
              $max = $count;
              $parents = $chain;
            }
          }

          $cats = $parents; // Has the longest chain
          
          // pre($cats);
          
          if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
          $html[] = $cats;
          if ($showCurrent == 1) $html[] = $before . get_the_title() . $after;
        } else {
          $post_type = get_post_type_object(get_post_type());
          $slug = $post_type->rewrite;
          $html[] = '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
          if ($showCurrent == 1) $html[] = ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
        }
        
      } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
        $post_type = get_post_type_object(get_post_type());
        $html[] = $before . $post_type->labels->singular_name . $after;

      } elseif ( is_attachment() ) {
        if ($showCurrent == 1) $html[] = $before . get_the_title() . $after;
        
      } elseif ( is_page() && !$post->post_parent ) {
        if ($showCurrent == 1) $html[] = $before . get_the_title() . $after;

      } elseif ( is_page() && $post->post_parent ) {
        $parent_id  = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
          $page = get_page($parent_id);
          $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
          $parent_id  = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        for ($i = 0; $i < count($breadcrumbs); $i++) {
          $html[] = $breadcrumbs[$i];
          if ($i != count($breadcrumbs)-1) $html[] = ' ' . $delimiter . ' ';
        }
        if ($showCurrent == 1) $html[] = ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

      } elseif ( is_tag() ) {
        $html[] = $before . sprintf('%s "', __("Posts tagged", "theme")) . single_tag_title('', false) . '"' . $after;

      } elseif ( is_author() ) {
         global $author;
        $userdata = get_userdata($author);
        $html[] = $before . sprintf('%s by ', __("Articles posted", "theme")) . $userdata->display_name . $after;

      } elseif ( is_404() ) {
        $html[] = $before . __("Error 404", "theme") . $after;
      }

      // if ( get_query_var('paged') ) {
      //   if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $html[] = ' (';
      //   $html[] = 'Page' . ' ' . get_query_var('paged');
      //   if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $html[] = ')';
      // }

      return implode("\n", $html);

    }

  }
  
}
  
  function theme_generator($gen) { global $der_framework;
    $t = $der_framework->theme_data;
    return $gen . sprintf("<!-- %s %s -->\n", $t->get('Name'), $t->get('Version'));
  }
  
  function theme_custom_css() { global $der_framework;
    
    $css = $der_framework->option('custom_css');
    
    if (empty($css)) $css = '';
    
    $css = apply_filters('theme_custom_css', $css);
    
    // Replace ascii char 160 with ascii char 32
    $css = str_replace(' ', ' ', $css);
    
    if (!empty($css)) {
     
      printf('<!-- Custom CSS -->
<style type="text/css">
%s
</style>'."\n\n", $css);
      
    }
    
  }
  
  function theme_custom_javascript() { global $der_framework;
    
    $js = $der_framework->option('custom_javascript');
    
    if (!empty($js)) {
      
      printf('<!-- Custom JavaScript -->
<script type="text/javascript">
(function($) {
%s
})(jQuery);
</script>'."\n\n", $js);
      
    }
    
  }
  
  function twentyeleven_comment( $comment, $args, $depth ) {
    
    // Uses code from the TwentyElevent (GPL)
    
  	$GLOBALS['comment'] = $comment;
  	
  	switch ( $comment->comment_type ) :
  		case 'pingback' :
  		case 'trackback' :
  	?>
  	<li class="post pingback">
  		<p><?php __('Pingback', "theme"); ?> <?php comment_author_link(); ?><?php edit_comment_link( __("Edit", "theme"), '<span class="edit-link">', '</span>' ); ?></p>
  	<?php
  			break;
  		default :
  	?>
  	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  		<article id="comment-<?php comment_ID(); ?>" class="comment">
  			<footer class="comment-meta">
  				<div class="comment-author vcard">
  					<?php
  						$avatar_size = 52;
  						if ( '0' != $comment->comment_parent )
  							$avatar_size = 52;

  						echo get_avatar( $comment, $avatar_size );

  						printf('%1$s on %2$s <span class="says">said:</span>',
  							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
  							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
  								esc_url( get_comment_link( $comment->comment_ID ) ),
  								get_comment_time('c'),
  							  // translators: 1: date, 2: time
  								sprintf('%1$s at %2$s', get_comment_date(), get_comment_time() )
  							)
  						);
  					?>
  					<?php edit_comment_link("Edit", '<span class="edit-link">', '</span>' ); ?>
  				</div><!-- .comment-author .vcard -->

  			</footer>

  			<div class="comment-content"><?php

  				if ( $comment->comment_approved == '0' ) printf('<em class="comment-awaiting-moderation">&mdash; %s &mdash;</em>', __("Your comment is awaiting moderation", "theme"));

  				comment_text();

  			?></div>

  			<div class="reply">
  				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reply', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
  			</div><!-- .reply -->
  		</article><!-- #comment-## -->

  	<?php
  			break;
  	endswitch;
  }
  
  function theme_get_image_mimetype($file) {
    $pathinfo = pathinfo($file);
    switch (strtolower($pathinfo['extension'])) {
      case 'png': return 'image/png';
      case 'jpg': case 'jpeg': return 'image/jpeg';
      case 'gif': return 'image/gif';
      case 'ico': return 'image/x-icon';
      case 'svg': return 'image/svg+xml';
      default: return 'application/octet-stream';
    }
  }
  
?>