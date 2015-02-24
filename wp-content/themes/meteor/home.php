<?php 

global $der_framework; 

get_header();

if ($der_framework->has_layout('homepage_layout')) {
  
  $der_framework->render_layout();
  
} else if (is_user_logged_in()) {
  
  $link = '<a href="' . admin_url('admin.php?page=theme-options#layouts') . '">' . __("Theme Options", "theme") . '</a>';
  
  $der_framework->message(sprintf('<div class="post-content"><p class="standout"><i class="icon-circle-arrow-right"></i> &nbsp; Set a Layout for your Homepage on the %s. &nbsp; Have Fun!</p></div>', $link));
  
}

get_footer();

?>