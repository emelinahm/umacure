<?php

if (!defined('ABSPATH')) die();

/* Layout Editor */

add_action('admin_menu', 'layout_editor_register_screen');
add_action('wp_ajax_layout_editor_update', 'layout_editor_update_callback');

if (isset($_GET['page']) && $_GET['page'] == 'layout-editor') {
  add_action('admin_print_scripts', 'layout_editor_print_scripts');
  add_action('admin_print_styles', 'layout_editor_print_styles');
}

/* Sends the backup to the user */

function layout_editor_backup() { global $der_framework;
  $filename = string2id(get_bloginfo('name')) . '-layouts.json';
  $data = $der_framework->get_option('layouts_json');
  header('Content-Type: application/json');
  header("Content-Disposition: attachment; filename=${filename}");
  echo $data;
  exit();
}

/* Layout Editor update action */

function layout_editor_update_callback() {
  require(TEMPLATEPATH . '/framework/layout-editor/layout-editor-action.php');
}

/* Registers the layout editor admin menu */

function layout_editor_register_screen() { global $der_framework;
  
  if (isset($_GET['backup']) && $_GET['backup'] == 1) {
    layout_editor_backup();
    exit();
  }
  
  __meteor_menu_page("Layout Editor", "Layouts", THEME_CAPABILITY, 'layout-editor', 'layout_Editor_render_page', $der_framework->uri('framework/assets/layout-editor.png', THEME_VERSION), 57);
}

/* Renders the layout editor page */

function layout_editor_render_page() { global $der_framework;
  
  if (phpversion() > '5.2.0') printf("\n".'<div id="layout-editor">');
  else printf("\n".'<div id="layout-editor" data-disabled=true>');
  
  include($der_framework->path('framework/theme-options/theme-options-interface.head.php'));
  include($der_framework->path('framework/layout-editor/layout-editor-canvas.php'));
  include($der_framework->path('framework/theme-options/theme-options-interface.foot.php'));
  printf("\n</div><!-- layout-editor -->");
}

/* Load styles */

function layout_editor_print_styles() { global $der_framework;
  wp_enqueue_style('layout-editor', $der_framework->uri('framework/layout-editor/layout-editor.css'), array(), THEME_VERSION);
}

/* Load JS Scritps */

function layout_editor_print_scripts() { global $der_framework;
  
  $echo = "";
  
  $json = $der_framework->get_option('layouts_json');

  $echo .= "\n".'<script type="text/javascript"><!--//--><![CDATA[//><!--'."\n";
  
  if ($json) {
    $json = preg_replace('/<!--(\s+)?(.*?)(\s+)?-->/', '', $json);
    $echo .= sprintf('  window.LAYOUT_DATA = %s;', $json);
  } else {
    $echo .= sprintf('  window.LAYOUT_DATA = {};');
  }
  
  // Print taxonomies
  
  $taxonomies = layout_editor_taxonomy_data();
  
  $echo .= sprintf("\n\n".'  window.WP_TAXONOMY_DATA = %s;', json_encode($taxonomies));
  
  // Print custom data
  
  $galleries = query_posts('post_type=gallery&showposts=-1'); wp_reset_query();
  $gallery_data = array();

  foreach ($galleries as $p) {
    $gallery_data[sprintf('%d', $p->ID)] = apply_filters('the_title', $p->post_title);
  }
  
  $data = array(
    'sidebars' => make_assoc_array(list2array($der_framework->option('sidebars')), '___'),
    'gallery-posts' => $gallery_data 
  );
  
  $data = apply_filters('layout_editor_custom_data', $data);
  
  $echo .= sprintf("\n\n".'  window.WP_CUSTOM_DATA = %s;', json_encode($data));
  
  $echo .= "\n".'//--><!]]></script>'."\n\n";
  
  echo $echo;
  
  wp_enqueue_script('json2');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script('jquery-ui-droppable');
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('layout-editor-components',	$der_framework->uri('includes/layout/components.js'), array('jquery'), THEME_VERSION);
  wp_enqueue_script('layout-editor-form',	$der_framework->uri('framework/layout-editor/layout-editor-form.js'), array('jquery'), THEME_VERSION);
  wp_enqueue_script('layout-editor-interface',	$der_framework->uri('framework/layout-editor/layout-editor.js'), array('jquery'), THEME_VERSION);

}


?>