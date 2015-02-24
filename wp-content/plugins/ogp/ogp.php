<?php
/*
Plugin Name: Open Graph Pro
Plugin URI: http://ten-fingers-and-a-brain.com/wordpress-plugins/ogp/
Version: 1.0
Description: Adds Open Graph tags to your blog. Control how your posts and pages are presented on Facebook and other social media sites. No configuration needed.
Author: Martin Lormes
Author URI: http://ten-fingers-and-a-brain.com/
Text Domain: ogp
*/
/*
Copyright (c) 2011 Martin Lormes

This program is free software; you can redistribute it and/or modify it under 
the terms of the GNU General Public License as published by the Free Software 
Foundation; either version 3 of the License, or (at your option) any later 
version.

This program is distributed in the hope that it will be useful, but WITHOUT 
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with 
this program. If not, see <http://www.gnu.org/licenses/>.
*/
/** Open Graph Pro (WordPress Plugin) */

// i18n/l10n
load_plugin_textdomain ( 'ogp', '', basename ( dirname ( __FILE__ ) ) );

/** Open Graph Pro (WordPress Plugin) functions wrapped in a class. (namespacing pre PHP 5.3) */
class ogp__open_graph_pro
{

  /**
   * let's put the Open Graph and Facebook namespaces in the <html> tag (if the theme supports it)
   *
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Filter_Reference WordPress filter}: language_attributes
   */
  function language_attributes ( $s )
  {
    if ( !is_admin() ) // we don't need this in the admin section
    {
      $options = get_option ( 'ogp' );
      /** @todo check if another plugin has already added the namespace information */
      $s .= ' xmlns:og="http://ogp.me/ns#"';
      // add Facebook Namespace in case the fb:admins or app_id have been set
      if ( is_array ( $options ) && ( ( isset ( $options['facebook']['admins'] ) && ( '' != $options['facebook']['admins'] ) ) || ( isset ( $options['facebook']['app_id'] ) && ( '' != $options['facebook']['app_id'] ) ) ) )
        $s .= ' xmlns:fb="http://www.facebook.com/2008/fbml"';
    }
    return $s;
  }
  
  /**
   * let's output the Open Graph tags
   *
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Action_Reference WordPress action}: wp_head
   */
  function wp_head ()
  {
    $options = get_option ( 'ogp' );
    // == let's set some default values
    
    // site_name - the name of the site
    $site_name = esc_attr ( get_option('blogname') );
    
    // image
    if ( is_array ( $options ) && isset ( $options['image']['url'] ) && ( '' != $options['image']['url'] ) )
      $image = esc_attr ( $options['image']['url'] );
    // use the header image, if available
    else
      $image = ( defined('HEADER_IMAGE') AND '' != get_header_image() ) ? esc_attr ( get_header_image() ) : '';
    
    $admins = ( is_array ( $options ) && isset ( $options['facebook']['admins'] ) && ( '' != $options['facebook']['admins'] ) ) ? $options['facebook']['admins'] : '';
    
    $app_id = ( is_array ( $options ) && isset ( $options['facebook']['app_id'] ) && ( '' != $options['facebook']['app_id'] ) ) ? $options['facebook']['app_id'] : '';
    
    $posts_on = !is_array ( $options ) || !isset ( $options['advanced']['posts_on'] ) || ( '1' == $options['advanced']['posts_on'] );
    $pages_on = !is_array ( $options ) || !isset ( $options['advanced']['pages_on'] ) || ( '1' == $options['advanced']['pages_on'] );
    
    // == if we're dealing with an individual post or page
    if ( ( is_single() && $posts_on ) || ( is_page() && $pages_on ) )
    {
      global $post;
      
      $meta = get_post_meta ( $post->ID, '_ogp__open_graph_pro', true );
      if ( is_array ( $meta ) && isset ( $meta['use_page'] ) )
      {
        $thispost = get_post ( $meta['use_page'] );
        $meta = get_post_meta ( $thispost->ID, '_ogp__open_graph_pro', true );
      }
      else
        $thispost = $post;
      
      // title - the post title
      $title = esc_attr ( $thispost->post_title );
      
      $type = ( is_array ( $meta ) && isset ( $meta['type'] ) ) ? $meta['type'] : 'article';
      
      // url - always use the permalink
      $url = esc_attr ( get_permalink($thispost->ID) );
      
      // image -- if we have any post images, use them; featured image (a.k.a. post thumbnail) will be preferred (if there's no image here, use header image from above)
      if ( !is_array ( $options ) || !isset ( $options['image']['headeronly'] ) || ( '1' != $options['image']['headeronly'] ) )
      {
        if ( function_exists ( 'has_post_thumbnail' ) AND has_post_thumbnail($thispost->ID) )
        {
          $attachment = wp_get_attachment_image_src ( get_post_thumbnail_id($thispost->ID) );
          $image = esc_attr ( $attachment[0] );
        }
        elseif ( preg_match ( '/<img\s[^>]*src=["\']?([^>"\']+)/i', $thispost->post_content, $match ) )
          $image = esc_attr ( $match[1] );
      }
      
      // description - use the excerpt if available, else use the content
      $description = strip_tags ( $thispost->post_excerpt ? $thispost->post_excerpt : $thispost->post_content );
      
      /** @todo Facebook user IDs from $meta */
    }
    // == else, i.e. if we're NOT dealing with an individual post or page
    else
    {
      // title - use the same value as for site_name
      $title = $site_name;
      
      // type
      if ( is_array ( $options ) && isset ( $options['type']['type'] ) && ( '' != $options['type']['type'] ) )
        $type = esc_attr ( $options['type']['type'] );
      // defaults to 'blog'
      else
        $type = 'blog';
      
      // url - always use the blog url
      $url = esc_attr ( get_bloginfo('url') );
      
      // description - use the tagline
      /** @todo this shall become editable on a settings page */
      $description = get_option('blogdescription');
    }
    
    // clean up the description, i.e. strip any html tags, completely normalize whitespace, etc., and truncate at 255 characters length
    $description = preg_replace ( '/\s+/', ' ', $description );
    if ( strlen ( $description ) > 255 ) $description = substr ( $description, 0, 252 ) . '...';
    $description = esc_attr ( $description );
    
    // == now let's actually output everything
    
    echo "<meta property=\"og:title\" content=\"$title\" />\n";
    echo "<meta property=\"og:site_name\" content=\"$site_name\" />\n";
    if ( '' != $description ) echo "<meta property=\"og:description\" content=\"$description\" />\n";
    echo "<meta property=\"og:type\" content=\"$type\" />\n";
    echo "<meta property=\"og:url\" content=\"$url\" />\n";
    if ( '' != $image ) echo "<meta property=\"og:image\" content=\"$image\" />\n";
    if ( '' != $admins ) echo "<meta property=\"fb:admins\" content=\"$admins\" />\n";
    if ( '' != $app_id ) echo "<meta property=\"fb:app_id\" content=\"$app_id\" />\n";
  }
  
  /**
   * register the plugin's functions with their respective hooks
   *
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Action_Reference WordPress action}: {@link http://codex.wordpress.org/Plugin_API/Action_Reference/init init}
   */
  function init ()
  {
    add_filter ( 'language_attributes', array ( 'ogp__open_graph_pro', 'language_attributes' ) );
    add_action ( 'wp_head', array ( 'ogp__open_graph_pro', 'wp_head' ) );
  }
  
  /**
   * @since 1.0
   */
  function settings_section__type ()
  {
    _e( '<p>By default the plugin labels your site a "blog". However, Open Graph supports various other object types.</p><p>You can change the object type <em>on a per-page basis on the page editing screens</em>. You should do that if you have multiple objects and a different page for each of them, e.g. the different products you sell, the different athletes on your team, one page per song or album of your band, etc.</p><p>You can change the object type <em>of your entire site</em> here.</p>', 'ogp' );
  }
  
  /**
   * @since 1.0
   */
  function settings_field__type__type ()
  {
    $options = get_option ( 'ogp' );
    $type = $options['type']['type'];
    ogp__open_graph_pro::ogp_dropdown_types ( $type, 'ogp[type][type]', 'ogp__type_type', array ( array ( 'Websites', 'article' ), ) );
  }

  /**
   * @since 1.0
   */
  function settings_section__image ()
  {
    // detect whether the current theme supports header images, etc.
    $imgsupport = 0;
    if ( defined ( 'HEADER_IMAGE' ) )                                                                         $imgsupport +=  1; // theme supports header images
    if ( ( 1 & $imgsupport ) && '' != get_header_image() )                                                    $imgsupport +=  2; // ... and there is one actually in use
    if ( ( 1 & $imgsupport ) && current_theme_supports ( 'custom-header-uploads' ) )                          $imgsupport +=  4; // theme supports uploading custom header image
    if ( current_theme_supports ( 'post-thumbnails', 'post' ) && post_type_supports ( 'post', 'thumbnail' ) ) $imgsupport +=  8; // theme supports featured images on posts
    if ( current_theme_supports ( 'post-thumbnails', 'page' ) && post_type_supports ( 'page', 'thumbnail' ) ) $imgsupport += 16; // theme supports featured images on pages
    
    if ( ( 1 & $imgsupport ) && ( 24 & $imgsupport ) )
    echo sprintf ( __ ( '<p>By default the plugin uses the featured image of your posts and pages. If your posts or pages do not have a featured image, the plugin looks for the first image in the post or page content. For all other uses (i.e. no image associated with the post or page, the home page with the latest posts, archives, category pages, etc.) the <a href="%s">theme\'s header image</a> is used.</p><p>To alter this behaviour change the settings below.</p>', 'ogp' ), 'themes.php?page=custom-header' );
    
    elseif ( 1 & $imgsupport )
    echo sprintf ( __( '<p>By default the plugin looks for the first image in the post or page content. For all other uses (i.e. no image associated with the post or page, the home page with the latest posts, archives, category pages, etc.) the <a href="%s">theme\'s header image</a> is used.</p><p>To alter this behaviour change the settings below.</p>', 'ogp' ), 'themes.php?page=custom-header' );
    
    elseif ( 24 & $imgsupport )
    _e( '<p>By default the plugin uses the featured image of your posts and pages. If your posts or pages do not have a featured image, the plugin looks for the first image in the post or page content. For all other uses (i.e. no image associated with the post or page, the home page with the latest posts, archives, category pages, etc.) the header image specified below will be used.</p>', 'ogp' );
    
    else
    _e( '<p>By default the plugin looks for the first image in the post or page content. For all other uses (i.e. no image associated with the post or page, the home page with the latest posts, archives, category pages, etc.) the header image specified below will be used.</p>', 'ogp' );
    
  }
  
  /**
   * @since 1.0
   */
  function settings_field__image__headeronly ()
  {
    $options = get_option ( 'ogp' );
    $headeronly = $options['image']['headeronly'];
    ?>
    <input type="checkbox" name="ogp[image][headeronly]" id="ogp__image_headeronly" value="1"<?php if ( '1' == $headeronly ) echo ' checked="checked"'; ?> />
    <label for="ogp__image_headeronly"><?php _e( 'Use header image only, i.e. do not look for images in posts or pages', 'ogp' ); ?></label>
    <?php
  }

  /**
   * @since 1.0
   */
  function settings_field__image__url ()
  {
    $options = get_option ( 'ogp' );
    $url = $options['image']['url'];
    ?>
    <input type="text" class="regular-text" name="ogp[image][url]" value="<?php echo htmlspecialchars ( $url ); ?>" />
    <span class="description">
      <?php _e( 'enter the URL of the image you want to see on Facebook &ndash; leave empty to use the theme\'s header image', 'ogp' ); ?>
    </span>
    <?php
  }

  /**
   * @since 1.0
   */
  function settings_section__facebook ()
  {
    _e( '<p>Admin users can post updates to the timeline of fans of their site or perform other administrative tasks on the Facebook Platform. You can also link your site to a Facebook Platform Application to be able to stream updates programatically.</p>', 'ogp' );
  }
  
  /**
   * @since 1.0
   */
  function settings_field__facebook__admins ()
  {
    $options = get_option ( 'ogp' );
    $admins = $options['facebook']['admins'];
    /** @todo allow users to enter profile links; use Graph API (JSON) to extract numerical user IDs from vanity URLs */
    ?>
    <input type="text" class="regular-text" name="ogp[facebook][admins]" value="<?php echo htmlspecialchars ( $admins ); ?>" />
    <span class="description">
      <?php echo sprintf ( __( 'enter a comma separated list of <a href="%1$s">numerical Facebook user IDs</a> &ndash; please note: users must "Like" the site (and each page respectively) to be approved as administrators', 'ogp' ), __( 'http://ten-fingers-and-a-brain.com/wordpress-plugins/ogp/facebook-id/', 'ogp' ) ); ?>
    </span>
    <?php
  }

  /**
   * @since 1.0
   */
  function settings_field__facebook__app_id ()
  {
    $options = get_option ( 'ogp' );
    $app_id = $options['facebook']['app_id'];
    /** @todo allow users to enter app links; use Graph API (JSON) to extract numerical IDs from vanity URLs */
    ?>
    <input type="text" class="regular-text" name="ogp[facebook][app_id]" value="<?php echo htmlspecialchars ( $app_id ); ?>" />
    <span class="description">
      <?php _e( 'enter the numerical Facebook Platform Application ID', 'ogp' ); ?>
    </span>
    <?php
  }

  /**
   * @since 1.0
   */
  function settings_section__advanced ()
  {
    _e( '<p>By default the plugin inserts Open Graph meta information for individual posts and for individual pages. Whenever someone likes or shares the URL of another part of your blog, e.g. an archive page, they are redirected to the main page of the blog/site.</p>', 'ogp' );
  }
  
  /**
   * @since 1.0
   */
  function settings_field__advanced__individual_on ()
  {
    $options = get_option ( 'ogp' );
    $posts_on     = !is_array ( $options ) || !isset ( $options['advanced']['posts_on'] )      || ( '1' == $options['advanced']['posts_on'] );
    $pages_on     = !is_array ( $options ) || !isset ( $options['advanced']['pages_on'] )      || ( '1' == $options['advanced']['pages_on'] );
    $authors_on    = is_array ( $options ) &&  isset ( $options['advanced']['authors_on'] )    && ( '1' == $options['advanced']['authors_on'] );
    $categories_on = is_array ( $options ) &&  isset ( $options['advanced']['categories_on'] ) && ( '1' == $options['advanced']['categories_on'] );
    ?>
    <input type="checkbox" name="ogp[advanced][posts_on]" id="ogp__advanced_posts_on" value="1"<?php if ( $posts_on ) echo ' checked="checked"'; ?> />
    <label for="ogp__advanced_posts_on"><?php _e( 'For Posts', 'ogp' ); ?></label>
    <br/>
    <input type="checkbox" name="ogp[advanced][pages_on]" id="ogp__advanced_pages_on" value="1"<?php if ( $pages_on ) echo ' checked="checked"'; ?> />
    <label for="ogp__advanced_pages_on"><?php _e( 'For Pages', 'ogp' ); ?></label>
    <?php /* ?>
    <br/>
    <input type="checkbox" name="ogp[advanced][authors_on]" id="ogp__advanced_authors_on" value="1"<?php if ( $authors_on ) echo ' checked="checked"'; ?> />
    <label for="ogp__advanced_authors_on"><?php _e( 'For Authors, i.e. treat each author\'s page as if it were an individual blog', 'ogp' ); ?></label>
    <br/>
    <input type="checkbox" name="ogp[advanced][categories_on]" id="ogp__advanced_categories_on" value="1"<?php if ( $categories_on ) echo ' checked="checked"'; ?> />
    <label for="ogp__advanced_categories_on"><?php _e( 'For Categories, i.e. treat each category as if it were an individual blog', 'ogp' ); ?></label>
    <?php */ ?>
    <?php
  }
  
  /**
   * sanitize plugin options, i.e. check and correct formatting before storing in database
   * @since 1.0
   */
  function sanitize__ogp ( $settings )
  {
    // check list of facebook admins for CSV and normalize
    $admins = trim ( $settings['facebook']['admins'] );
    $admins = preg_replace ( '/\s+/', ' ', $admins );
    $admins = preg_replace ( '/[^0-9,; ]/', '', $admins );
    $admins = preg_replace ( '/\s*[,;]\s*/', ',', $admins );
    $admins = preg_replace ( '/\s+/', ',', $admins );
    $admins = preg_replace ( '/,+/', ',', $admins );
    
    return array (
      'type' => array (
        'type' => $settings['type']['type'],
      ),
      'image' => array (
        'headeronly' => ( isset ( $settings['image']['headeronly'] ) AND '1' == $settings['image']['headeronly'] ) ? '1' : '0',
        'url' => trim ( $settings['image']['url'] ),
      ),
      'facebook' => array (
        'admins' => $admins, // sanitization see above
        'app_id' => preg_replace ( '/[^0-9]/', '', $settings['facebook']['app_id'] ),
      ),
      'advanced' => array (
        'posts_on' => ( isset ( $settings['advanced']['posts_on'] ) AND '1' == $settings['advanced']['posts_on'] ) ? '1' : '0',
        'pages_on' => ( isset ( $settings['advanced']['pages_on'] ) AND '1' == $settings['advanced']['pages_on'] ) ? '1' : '0',
        'authors_on' => ( isset ( $settings['advanced']['authors_on'] ) AND '1' == $settings['advanced']['authors_on'] ) ? '1' : '0',
        'categories_on' => ( isset ( $settings['advanced']['categories_on'] ) AND '1' == $settings['advanced']['categories_on'] ) ? '1' : '0',
      ),
    );
  }

  /**
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Filter_Reference WordPress filter}: {@link http://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links plugin_action_links}
   * @since 1.0
   */
  function plugin_action_links ( $links )
  {
    $links[] = sprintf ( '<a href="options-general.php?page=ogp">%s</a>', __( 'Settings' ) ); // 'Settings' is in the default domain!
    return $links;
  }
  
  /**
   * @since 1.0
   */
  function meta_box ( $p )
  {
    if ( 0 != $p->ID ) // this is not being created new
      $meta = get_post_meta ( $p->ID, '_ogp__open_graph_pro', true );
    if ( !isset ( $meta ) || ( '' == $meta ) )
      $meta = array ( 'use_page' => '', 'type' => 'article', 'fb_admins' => '', );
    
    wp_nonce_field ( plugin_basename( __FILE__ ), '_ogp_nonce' );
    
    ?>
    
    <?php if ( 'page' == $p->post_type ) : ?>
      <p><label for="_ogp__open_graph_pro[use_page]"><?php _e( 'Use metadata of the following page, e.g. if this is a page about a product feature and you want users to "Like" the entire product, not just the feature:', 'ogp' ); ?></label><br/>
      <?php wp_dropdown_pages ( array ( 'name' => '_ogp__open_graph_pro[use_page]', 'selected' => $meta['use_page'], 'show_option_none' => __( '-- this page --', 'ogp' ) ) ); ?>
      </p>
      
      <p><label for="ogp__type"><?php _e( 'Change the object type for this page to:', 'ogp' ); ?></label><br/>
      <?php ogp__open_graph_pro::ogp_dropdown_types ( $meta['type'], '_ogp__open_graph_pro[type]', 'ogp__type', array ( array ( 'Websites', 'blog' ), array ( 'Websites', 'website' ), ) ); ?>
      </p>
      
      <?php /* ?>
      <p><label for="ogp__fb_admins"><?php _e( 'Add the following Facebook users as administrators to this page:', 'ogp' ); ?></label><br/>
      <input type="text" name="_ogp__open_graph_pro[fb_admins]" id="ogp__fb_admins" style="width:99%" value="<?php echo esc_attr ( $meta['fb_admins'] ); ?>"/><br/>
      <?php echo sprintf ( __( '(enter a comma separated list of <a href="%1$s">numerical Facebook user IDs</a> &ndash; please note: users must "Like" the page to be approved as administrators)', 'ogp' ), __( 'http://ten-fingers-and-a-brain.com/wordpress-plugins/ogp/facebook-id/', 'ogp' ) ); ?></p>
      <?php //*/ ?>
      
    <?php elseif ( 'post' == $p->post_type ) : ?>
      <p><label for="_ogp__open_graph_pro[use_page]"><?php _e( 'Use metadata of the following page, e.g. if this is a post about a product upgrade and you want users to "Like" the product, not the announcement:', 'ogp' ); ?></label><br/>
      <?php wp_dropdown_pages ( array ( 'name' => '_ogp__open_graph_pro[use_page]', 'selected' => $meta['use_page'], 'show_option_none' => __( '-- this post --', 'ogp' ) ) ); ?>
      </p>
      
    <?php endif; ?>
    
    <?php
    
    /** @todo change image, excerpt */
    
    /* DEBUG
    echo '<pre>';
    print_r ( $p );
    echo '</pre>';
    //*/
    /* DEBUG
    
    echo '<pre>';
    print_r ( $meta );
    echo '</pre>';
    //*/
  }
  
  /**
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Action_Reference WordPress action}: save_post
   * @since 1.0
   */
  function save_post ( $postid )
  {
    // don't do anything if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    
    // don't do anything if the nonce fails
    if ( !isset ( $_POST['_ogp_nonce'] ) || !wp_verify_nonce ( $_POST['_ogp_nonce'], plugin_basename( __FILE__ ) ) ) return;

    // don't do anything if the user doesn't have sufficient permissions
    if ( ( 'page' == $_POST['post_type'] ) && !current_user_can( 'edit_page', $postid ) ) return;
    if ( ( 'post' == $_POST['post_type'] ) && !current_user_can( 'edit_post', $postid ) ) return;
    
    // get form data
    $formdata = $_POST['_ogp__open_graph_pro'];
    
    /** @todo sanitize */
    $meta = array (
      'use_page' => ( isset ( $formdata['use_page'] ) ) ? $formdata['use_page'] : '',
      'type' => ( isset ( $formdata['type'] ) ) ? $formdata['type'] : 'article',
      'fb_admins' => ( isset ( $formdata['fb_admins'] ) ) ? $formdata['fb_admins'] : '',
    );
    
    // write to database
    update_post_meta ( $postid, '_ogp__open_graph_pro', $meta );
  }
  
  /**
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Action_Reference WordPress action}: {@link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init admin_init}
   * @since 1.0
   */
  function admin_init ()
  {
    add_settings_section ( 'ogp_type', __( 'Object Type', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_section__type' ), 'ogp' );
    add_settings_field ( 'ogp_type_type', __( 'Set Object Type to', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_field__type__type' ), 'ogp', 'ogp_type' );
    
    add_settings_section ( 'ogp_image', __( 'Image', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_section__image' ), 'ogp' );
    add_settings_field ( 'ogp_image_url', ( ( defined ( 'HEADER_IMAGE' ) ) ? __( 'Replace Header Image with', 'ogp' ) : __( 'Header Image', 'ogp' ) ), array ( 'ogp__open_graph_pro', 'settings_field__image__url' ), 'ogp', 'ogp_image' );
    add_settings_field ( 'ogp_image_headeronly', __( 'Use Header Image only', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_field__image__headeronly' ), 'ogp', 'ogp_image' );
    
    add_settings_section ( 'ogp_facebook', __( 'Facebook', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_section__facebook' ), 'ogp' );
    add_settings_field ( 'ogp_facebook_admins', __( 'Admin User(s)', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_field__facebook__admins' ), 'ogp', 'ogp_facebook' );
    add_settings_field ( 'ogp_facebook_app_id', __( 'Application ID', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_field__facebook__app_id' ), 'ogp', 'ogp_facebook' );
    
    add_settings_section ( 'ogp_advanced', __( 'Advanced Settings', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_section__advanced' ), 'ogp' );
    add_settings_field ( 'ogp_advanced_individual_on', __( 'Turn Open Graph on', 'ogp' ), array ( 'ogp__open_graph_pro', 'settings_field__advanced__individual_on' ), 'ogp', 'ogp_advanced' );
    
    register_setting ( 'ogp', 'ogp', array ( 'ogp__open_graph_pro', 'sanitize__ogp' ) );

    add_filter ( 'plugin_action_links_' . plugin_basename ( __FILE__ ), array ( 'ogp__open_graph_pro', 'plugin_action_links' ) );
    
    add_meta_box ( 'ogp__open_graph_pro', __( 'Open Graph Protocol (Facebook)', 'ogp' ), array ( 'ogp__open_graph_pro', 'meta_box' ), 'post', 'normal', 'high' );
    add_meta_box ( 'ogp__open_graph_pro', __( 'Open Graph Protocol (Facebook)', 'ogp' ), array ( 'ogp__open_graph_pro', 'meta_box' ), 'page', 'normal', 'high' );
    add_action ( 'save_post', array ( 'ogp__open_graph_pro', 'save_post' ) );
  }

  /**
   * @since 1.0
   */
  function options_page ()
  {
    ?>
    <div class="wrap">
      <div id="icon-options-general" class="icon32"><br></div>
      <h2><?php _e( 'Open Graph Pro Settings', 'ogp' ); ?></h2>
      <form method="post" action="options.php">
        <?php do_settings_sections ( 'ogp' ); ?>
        <?php settings_fields ( 'ogp' ); ?>
        <p class="submit"><input class="button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes' ); // this is in the default domain! ?>" /></p>
      </form>
      <!--
      <a href="http://developers.facebook.com/tools/lint?url=<?php echo urlencode ( get_bloginfo('url') ); ?>">Lint</a>
      -->
    </div>
    <?php
  }

  /**
   * hooked to {@link http://codex.wordpress.org/Plugin_API/Action_Reference WordPress action}: {@link http://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu admin_menu}
   * @since 1.0
   */
  function admin_menu ()
  {
    $page = add_options_page ( __( 'Open Graph Pro Settings', 'ogp' ), __( 'Open Graph Pro', 'ogp' ), 'manage_options', 'ogp', array ( 'ogp__open_graph_pro', 'options_page' ) );
    /* translators: %1$s is the plugin name, %2$s is the Plugin URI, %3$s links to the Open Graph Protocol website, %4$s links to the Open Graph Protocol page at Facebook */
    $help = sprintf ( __( '<p><em>%1$s</em> automagically adds Open Graph tags to your blog. Control how your posts and pages are presented on Facebook and other social media sites.</p><p><strong>No configuration needed</strong>: The default settings should work just fine for most blogs. For everyone else the settings page should be pretty self explaining. If you find that it is not, you can contact the author of this plugin via his blog at <a href="%2$s">ten-fingers-and-a-brain.com</a></p><p>For more information on the Open Graph protocol go to the <a href="%3$s">official Open Graph website at ogp.me</a> or consult the <a href="%4$s">Developer section at facebook.com</a></p>', 'ogp' ), __( 'Open Graph Pro', 'ogp' ), __( 'http://ten-fingers-and-a-brain.com/wordpress-plugins/ogp/', 'ogp' ), __( 'http://27i.de/ogp', 'ogp' ), __( 'http://27i.de/fbdog', 'ogp' ) );
    add_contextual_help ( $page, $help );
  }
  
  /**
   * displays a dropdown (HTML select element) with all available object types
   * @since 1.0
   */
  function ogp_dropdown_types ( $selected = '', $name = 'object_type', $id = 'object_type', $exclude = array () )
  {
    $types_by_category = array (
      'Websites' => array ( 'name' => __( 'Websites', 'ogp' ), 'types' => array (
        'blog' => __( 'Blog', 'ogp' ),
        'website' => __( 'Website', 'ogp' ),
        'article' => __( 'Article', 'ogp' ),
      ), ),
      'Activities' => array ( 'name' => __( 'Activities', 'ogp' ), 'types' => array (
        'activity' => __( 'Activity', 'ogp' ),
        'sport' => __( 'Sport', 'ogp' ),
      ), ),
      'Businesses' => array ( 'name' => __( 'Businesses', 'ogp' ), 'types' => array (
        'bar' => __( 'Bar', 'ogp' ),
        'company' => __( 'Company', 'ogp' ),
        'cafe' => __( 'Cafe', 'ogp' ),
        'hotel' => __( 'Hotel', 'ogp' ),
        'restaurant' => __( 'Restaurant', 'ogp' ),
      ), ),
      'Groups' => array ( 'name' => __( 'Groups', 'ogp' ), 'types' => array (
        'cause' => __( 'Cause', 'ogp' ),
        'sports_league' => __( 'Sports League', 'ogp' ),
        'sports_team' => __( 'Sports Team', 'ogp' ),
      ), ),
      'Organizations' => array ( 'name' => __( 'Organizations', 'ogp' ), 'types' => array (
        'band' => __( 'Band', 'ogp' ),
        'government' => __( 'Government', 'ogp' ),
        'non_profit' => __( 'Non-Profit', 'ogp' ),
        'school' => __( 'School', 'ogp' ),
        'university' => __( 'University', 'ogp' ),
      ), ),
      'People' => array ( 'name' => __( 'People', 'ogp' ), 'types' => array (
        'actor' => __( 'Actor', 'ogp' ),
        'athlete' => __( 'Athlete', 'ogp' ),
        'author' => __( 'Author', 'ogp' ),
        'director' => __( 'Director', 'ogp' ),
        'musician' => __( 'Musician', 'ogp' ),
        'politician' => __( 'Politician', 'ogp' ),
        'public_figure' => __( 'Public Figure', 'ogp' ),
      ), ),
      'Places' => array ( 'name' => __( 'Places', 'ogp' ), 'types' => array (
        'city' => __( 'City', 'ogp' ),
        'country' => __( 'Country', 'ogp' ),
        'landmark' => __( 'Landmark', 'ogp' ),
        'state_province' => __( 'State or Province', 'ogp' ),
      ), ),
      'Products and Entertainment' => array ( 'name' => __( 'Products and Entertainment', 'ogp' ), 'types' => array (
        'album' => __( 'Album', 'ogp' ),
        'book' => __( 'Book', 'ogp' ),
        'drink' => __( 'Drink', 'ogp' ),
        'food' => __( 'Food', 'ogp' ),
        'game' => __( 'Game', 'ogp' ),
        'product' => __( 'Product', 'ogp' ),
        'song' => __( 'Song', 'ogp' ),
        'movie' => __( 'Movie', 'ogp' ),
        'tv_show' => __( 'TV Show', 'ogp' ),
      ), ),
    );
    if ( !empty ( $exclude ) )
    {
      foreach ( $exclude as $i )
      {
        if ( isset ( $types_by_category[$i[0]]['types'][$i[1]] ) )
          unset ( $types_by_category[$i[0]]['types'][$i[1]] );
      }
    }
    ?>
    <select name="<?php echo $name; ?>" id="<?php echo $id; ?>">
      <?php foreach ( $types_by_category as $category ) if ( !empty ( $category['types'] ) ) : ?>
        <optgroup label="<?php echo $category['name']; ?>">
          <?php foreach ( $category['types'] as $option_value => $option_name ) : ?>
            <option value="<?php echo $option_value; ?>"<?php if ( $selected == $option_value ) echo ' selected="selected"'; ?>><?php echo $option_name; ?></option>
          <?php endforeach; ?>
        </optgroup>
      <?php endif; ?>
    </select>
    <?php
  }

} // class ogp__open_graph_pro

// GO!
add_action ( 'init', array ( 'ogp__open_graph_pro', 'init' ) );
add_action ( 'admin_init', array ( 'ogp__open_graph_pro', 'admin_init' ) );
add_action ( 'admin_menu', array ( 'ogp__open_graph_pro', 'admin_menu' ) );
