<?php

  global $der_framework;

  $count = get_comments_number();

?>
<div class="meteor-comments" id="comments">  

<?php

  if ($count > 0) {
    
    switch ($count) {
      case 1:
        $comments_str = __("One Comment", "theme");
        break;
      default:
        $comments_str = sprintf(__("%d Comments", "theme"), $count);
        break;
    }
    
    printf('  <h3 class="inner-heading">%s</h3>'."\n", $comments_str);

?>
  <ol class="commentlist">
<?php

  wp_list_comments(array(
    'callback' => 'twentyeleven_comment',
    'avatar_size' => 52
  ));

?>
  </ol>
<?php

  }

?>
  <div id="respond">
    
    <span class="respond-close"><a><i class="icon-remove"></i></a></span>
    
    <span class="cancel-reply-link"><?php cancel_comment_reply_link(__("Cancel reply", "theme")); ?></span>
    <h3 class="inner-heading" id="reply-title">
      <?php echo __("Leave a Reply", "theme"); ?>
    </h3>
    
<?php if (get_option('comment_registration') && !is_user_logged_in()) { ?>
    <p><?php

      $logged_in = sprintf('<a href="%s">%s</a>',  wp_login_url(get_permalink()), __("logged in", "theme"));
      printf(__("You must be %s to post a comment.", "theme"), $logged_in);

?></p>
<?php } else { ?>
  
    <form class="meteor-form" id="commentform" method="post" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php">
  
<?php   if (is_user_logged_in()) { 
  
          echo $der_framework->render('
      <p class="logged-in-as standout">
         {{login_label}} &nbsp;<a href="{{{site_url}}}//wp-admin/profile.php" class="link-underline">{{{user_identity}}}</a>.
          <a class="notd link-hover-accent" href="{{{logout_url}}}" style="margin-left: 1em;">{{logout_label}} &nbsp;<i class="icon-signout"></i></a>
      </p><!-- .logged-in-as -->', array(
            
            'site_url' => get_option('siteurl'),
            'user_identity' => $user_identity,
            'logout_url' => wp_logout_url(get_permalink()),
            'logout_label' => __("Log out", "theme"),
            'login_label' => __("Logged in as", "theme")
            
          ));
  
  } else { ?>
    
    <div class="dual-container entry clearfix">
      <div class="half">
        <label class="required" for="cmf-author"><?php echo __("Your Name", "theme"); ?></label>
        <input id="cmf-author" class="required" type="text" name="author" value="" />
      </div><!-- .half -->
      <div class="half">
        <label class="required" for="cmf-email"><?php echo __("Your Email", "theme"); ?></label>
        <input id="cmf-email" class="required" type="text" name="email" value="" />
      </div><!-- .half -->
    </div><!-- .dual-container -->
    
    <p class="entry">
      <label for="cmf-website"><?php echo __("Your Website", "theme"); ?></label>
      <input id="cmf-website" type="text" name="url" value="" />
    </p><!-- .entry -->
  
<?php } ?>
  
      <p class="entry">
        <label class="required" for="cmf-message"><?php echo __("Your Message", "theme") ?></label>
        <textarea id="cmf-message" class="autosize required" rows="5" cols="80" name="comment"></textarea>
      </p><!-- .entry -->
      
<?php

    comment_id_fields();
    do_action('comment_form', get_the_ID());

?>

      <p class="entry">
        <input type="submit" id="cmf-submit" value="<?php echo __("Submit Comment", "theme") ?>" data-size="large" />
      </p><!-- .entry -->

    </form><!-- commentform -->
  
<?php } ?>

  </div><!-- respond -->

</div><!-- .comments -->