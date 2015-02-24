<?php

  global $der_framework, $content_width;

  $favicon = $der_framework->option('favicon');

  $grid = $der_framework->option('content_width'); // Default is set

  switch ($grid) {
    case '960': $grid = 980; break;
    case '1170': $grid = 1200; break;
  }

  $boxed_layout = $der_framework->option_bool('boxed_layout');

  $sticky_header = ($boxed_layout === false) && $der_framework->option_bool('sticky_header');

?><!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9]><html class="ie ie9 gt8" <?php language_attributes(); ?>><![endif]-->
<!--[if (gt IE 9)]><html class="ie gt8 gt9" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE)]><!--><html class="not-ie" <?php language_attributes(); ?>><!--<![endif]-->
<head>

<title><?php wp_title('&#150;',true, 'right'); bloginfo('name'); ?></title>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<?php

  if ($favicon) {

?>
<link rel="shortcut icon" type="<?php echo theme_get_image_mimetype($favicon); ?>" href="<?php echo $favicon; ?>" />
<?php

  } else {

?>
<link rel="shortcut icon" type="image/png" href="<?php echo $der_framework->uri("core/images/favicon.png", THEME_VERSION); ?>" />
<?php

  }

  $ios_display = $der_framework->option('ios_icon_display') == 'normal' ? "apple-touch-icon" : "apple-touch-icon-precomposed";

  $touch_icons = array(
    'touch_icon_57' => 57,
    'touch_icon_72' => 72,
    'touch_icon_114' => 114,
    'touch_icon_144' => 144
  );

  foreach ($touch_icons as $key => $size) {
    $val = $der_framework->option($key);
    if ($val) {
      printf('<link rel="%s" sizes="%dx%d" href="%s" />' . "\n", $ios_display, $size, $size, $val);
    }
  }

?>

<?php wp_head() ?>

</head>
<body <?php body_class() ?> data-widescreen="<?php echo ($grid == 1200) ? 'true' : 'false'; ?>">

<?php if ($boxed_layout): ?><div id="boxed-wrapper"><?php endif; ?>


