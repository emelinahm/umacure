<?php

  global $der_framework;
  
  $ajaxurl = admin_url('admin-ajax.php?action=layout_editor_update');

?>

<!-- [/options] -->

    </div><!-- content -->
    
    <div class="clear"></div>
    <span class="bg"></span>
  </div><!-- content-wrap -->

  <!-- + -->

  <div id="bottom-bar" class="options-bar">
<?php if (defined('THEME_OPTIONS') && THEME_OPTIONS): ?>
    <a class="btn btn-light reset-settings" href="<?php echo $ajaxurl; ?>">Reset Settings</a>
    <input type="submit" class="btn btn-primary save-settings" value="Save Settings" />
<?php else: ?>
    <a class="btn btn-light reset-layout" href="#">Reset Layouts</a>
    <input type="submit" class="btn btn-primary save-layout" value="Save Layouts" />
<?php endif; ?>
  </div><!-- bottom-bar -->

</div><!-- theme-options -->

<input type="hidden" name="options-context" value="<?php echo $der_framework->options_context; ?>" />
<?php $der_framework->nonce(); ?>

<?php if (defined('THEME_OPTIONS') && THEME_OPTIONS): ?>
<?php do_action('theme_options_hidden_fields'); ?>
</form><!-- options-form -->
<?php else: ?>
<form id="layout-save" method="post" action="<?php echo $ajaxurl; ?>">
  <input type="hidden" name="data_json" value="" />
<?php echo $der_framework->nonce(); ?>
</form><!-- layout-save -->

<?php do_action('theme_options_foot'); ?>

<?php endif; ?>

<!-- [/theme-options] -->

