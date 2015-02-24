<?php

  global $der_framework;
  
  $boxed_layout = $der_framework->option_bool('boxed_layout');

  if ($der_framework->load_maps_api) echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>'."\n";
  
  if (THEME_RELEASE) {
  
    printf('<script type="text/javascript" src="%s"></script>'."\n", $der_framework->uri("core/javascript-core.js", THEME_VERSION));

  } else {
    
    theme_development_javascript();
    
  }

?>

<?php if ($boxed_layout): ?></div><!-- boxed-wrapper --><?php endif; ?>

<!--[if IE]>
<script type="text/javascript" src="<?php echo $der_framework->uri("core/js/legacy/ie.js", THEME_VERSION); ?>"></script>
<![endif]-->

<!-- Core JavaScript -->
<script type="text/javascript">
(function($) {
  Core.initialize();
  Core.version = "<?php echo THEME_VERSION; ?>";
  Core.root = "<?php echo $der_framework->uri() ?>";
  Core.admin_url = "<?php echo admin_url() ?>";
  Core.options = {
    native_video_support: <?php echo $der_framework->option_bool('native_video') ? "true\n" : "false\n" ?>
  }
  Core.i18n = {
    author: "<?php echo __("Author", "theme"); ?>",
    admin: "<?php echo __("Admin", "theme") ?>",
    lt_minute_ago: "<?php echo __("less than a minute ago", "theme") ?>",
    abt_minute_ago: "<?php echo __("about a minute ago", "theme") ?>",
    minutes_ago: "<?php echo __("%s minutes ago", "theme") ?>",
    abt_hour_ago: "<?php echo __("about an hour ago", "theme") ?>",
    abt_hours_ago: "<?php echo __("about %s hours ago", "theme") ?>",
    one_day_ago: "<?php echo __("1 day ago", "theme") ?>",
    days_ago: "<?php echo __("%s days ago", "theme") ?>"
  }
})(jQuery);
</script>

<?php

  $analytics = $der_framework->option('analytics');
  
  if ($analytics) printf("<!-- analytics -->\n%s\n\n", $analytics);

?>
