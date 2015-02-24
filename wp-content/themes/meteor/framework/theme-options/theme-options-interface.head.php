<?php

  global $der_framework;
  
  if (is_child_theme()) {
    $theme = wp_get_theme();
  }
  
?>
<!-- [theme-options] -->

<?php if (defined('THEME_OPTIONS') && THEME_OPTIONS): ?><form id="options-form" method="post" action="<?php echo admin_url('admin-ajax.php?action=theme_options_update'); ?>"><?php endif; ?>

<?php do_action('theme_options_head'); ?>

<div id="theme-options">
  
  <div id="options-header">
    <div class="logo"></div>
    <span class="version"><?php if (is_child_theme()): echo '<strong>' . $theme->get("Name") . '</strong> ' . $theme->get('Version') . ' &nbsp;/&nbsp; ' . $der_framework->theme_data->get('Name'); ?>&nbsp;<?php endif; ?><?php echo $der_framework->theme_data->get('Version'); ?></span>
  </div><!-- header -->

  <!-- + -->
  
  <div id="top-bar" class="options-bar">
    <ul class="links">
      <li><a target="_blank" href="http://docs.der-design.com/<?php echo THEME_ID ?>"><i class="icon-book"></i>&nbsp; Documentation</a></li>
      <li><a class="icon-chooser-preview" target="_blank" href="#"><i class="icon-book"></i>&nbsp; Icon Reference</a></li>
      <li><a target="_blank" href="http://support.der-design.com"><i class="icon-question-sign"></i>&nbsp; Support Forums</a></li>
    </ul><!-- .links -->
<?php if (defined('THEME_OPTIONS') && THEME_OPTIONS): ?>
    <input type="submit" class="btn btn-primary save-settings" value="Save Settings" />
<?php else: ?>
    <input type="submit" class="btn btn-primary save-layout" value="Save Layouts" />
<?php endif; ?>
    <i id="ajaxload"></i>
  </div><!-- top-bar -->
  
  <!-- + -->
  
  <div id="content-wrap">
    
    <div id="sections">
<?php if (defined('THEME_OPTIONS') && THEME_OPTIONS): ?>
  <ul class="sections-list">

  </ul><!-- .sections-list -->
<?php else: ?>
  
  <div class="actions">
    <strong>Layouts</strong>
    <ul class="buttons">
      <li><a title="Toggle layout edit mode" class="btn-icon edit" href="#" onclick="return false;"></a></li>
      <li><a title="Duplicate Current Layout" class="btn-icon copy" href="#" onclick="return false;"></a></li>
      <li><a title="Add new layout" class="btn-icon add" href="#" onclick="return false;"></a></li>
    </ul><!-- .buttons -->
  </div><!-- .actions -->
  
  <ul class="sections-list">

  </ul><!-- .sections-list -->
  
<?php endif; ?>
    </div><!-- sections -->
    
    <div id="content">

<!-- [options] -->
<?php

  if (defined('THEME_OPTIONS') && THEME_OPTIONS && isset($_GET['success']) && $_GET['success'] === 'true') {
    
    printf('<div class="updated">%s</div>', "Settings Saved");
    
  }

?>