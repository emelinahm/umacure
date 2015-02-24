<?php

  if (!defined('ABSPATH')) die();

  require('social-icons-common.php');

  global $der_framework;
  
  // Prevent direct access
  if (empty($der_framework)) die('-1:0');

  $icons = get_theme_social_icons();
  
  // Generate select input
  $select = array('<select id="social-manager-select">');
  foreach ($icons as $id => $img) {
    $select[] = sprintf('<option value="%s">%s</option>', $id, $id);
  }
  $select[] = '</select>';
  $select = implode("\n", $select);
  
  // Get data
  $data = $der_framework->get_option('social_data');
  $data_available = !empty($data);
  
  $offset = 25;
  
?>
<div id="wpbody-content">
  
  <div class="wrap">
  
  <div id="screen-meta-links"></div>
    <div id="icon-options-general" class="icon32"><br></div><h2>Manage Social Icons</h2>

    <div id="social-manager" class="tool-box">
      <p>Choose a social network from the list below, then click on add. You can drag &amp; drop to reorder items</p>
<?php
  
      echo $select;
?>
      &nbsp; <a id="social-manager-add" class="button" href="#">Add</a>
      
      <form id="social-form" method="post" action="<?php echo admin_url('admin-ajax.php?action=social_icons_update') ?>">
        
        <div class="entries">
<?php if ($data_available): foreach ($data as $id => $vals): ?>
          <div class="item-entry <?php echo $id; ?>" data-id="<?php echo $id; ?>" style="padding-left: <?php echo SOCIAL_ICONS_WIDTH + $offset; ?>px;">
            <i class="icon" style="<?php printf('left: %dpx; width: %dpx; height: %dpx; background-image: url(%s);', ($offset/2), SOCIAL_ICONS_WIDTH, SOCIAL_ICONS_HEIGHT, $der_framework->uri(SOCIAL_ICONS_PATH . '/' . $id . '.png', THEME_VERSION)); ?>"></i>
            <a class="button remove" href="#">Remove</a>
            <label><input type="text" name="social[<?php echo $id; ?>][title]" value="<?php echo $vals['title'] ?>" tabindex="1" autocomplete="off" /> &nbsp; Title</label>
            <label><input type="text" name="social[<?php echo $id; ?>][url]" value="<?php echo $vals['url'] ?>" tabindex="1" autocomplete="off" /> &nbsp; URL</label>
          </div><!-- .item-entry -->
<?php endforeach; endif; ?>
           </div><!-- .entries -->

          <div class="button-box" style="display: <?php echo ($data_available) ? 'block' : 'none'; ?>;">
            <input type="submit" class="button-primary" value="Save Settings" />
          </div><!-- .submit -->
          
          <?php $der_framework->nonce(); ?>
          
        </form><!-- social-form -->
      
        <div id="item-entry-template">
          <div class="item-entry {{{id}}}" data-id="{{{id}}}" style="padding-left: <?php echo SOCIAL_ICONS_WIDTH + $offset; ?>px;">
            <i class="icon" style="<?php printf('left: %dpx; width: %dpx; height: %dpx; background-image: url(%s);', ($offset/2), SOCIAL_ICONS_WIDTH, SOCIAL_ICONS_HEIGHT, $der_framework->uri(SOCIAL_ICONS_PATH . '/{{{id}}}.png', THEME_VERSION)); ?>"></i>
            <a class="button remove" href="#">Remove</a>
            <label><input type="text" name="social[{{{id}}}][title]" value="" tabindex="1" autocomplete="off" /> &nbsp; Title</label>
            <label><input type="text" name="social[{{{id}}}][url]" value="" tabindex="1" autocomplete="off" /> &nbsp; URL</label>
          </div><!-- .item-entry -->
        </div><!-- .item-entry-template -->
        
    </div><!-- .tool-box -->
    
  </div><!-- .wrap -->

  <div class="clear"></div>

</div><!-- wpbody-content -->
